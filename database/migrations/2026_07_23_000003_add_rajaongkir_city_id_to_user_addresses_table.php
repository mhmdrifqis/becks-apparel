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
        Schema::table('user_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('user_addresses', 'rajaongkir_city_id')) {
                $table->string('rajaongkir_city_id')->nullable()->after('kota');
            }
            if (!Schema::hasColumn('user_addresses', 'rajaongkir_province_id')) {
                $table->string('rajaongkir_province_id')->nullable()->after('provinsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            if (Schema::hasColumn('user_addresses', 'rajaongkir_city_id')) {
                $table->dropColumn('rajaongkir_city_id');
            }
            if (Schema::hasColumn('user_addresses', 'rajaongkir_province_id')) {
                $table->dropColumn('rajaongkir_province_id');
            }
        });
    }
};
