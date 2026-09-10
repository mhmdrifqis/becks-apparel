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
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('materials', 'image_path')) {
                $table->string('image_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('materials', 'description')) {
                $columnsToDrop[] = 'description';
            }
            if (Schema::hasColumn('materials', 'image_path')) {
                $columnsToDrop[] = 'image_path';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
