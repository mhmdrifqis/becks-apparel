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
        Schema::table('order_items', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->decimal('material_usage', 10, 2)->nullable()->after('subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('material_usage');
        });
    }
};
