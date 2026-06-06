<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['Standard', 'Premium'])->default('Standard');
            $table->json('allowed_categories')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('status', ['Ready', 'Out of Stock'])->default('Ready');
            $table->decimal('additional_price', 15, 2)->default(0);
            $table->decimal('stock', 10, 2)->default(0);
            $table->string('unit')->default('Meter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
