<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('api_settings')) {
            try {
                $columns = [
                    'paywuz_sandbox_api_key',
                    'paywuz_production_api_key',
                    'rajaongkir_api_key',
                    'fonnte_token',
                    'gemini_api_key',
                    'google_client_id',
                    'google_client_secret',
                    'biteship_api_key',
                    'smtp_password',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('api_settings', $column)) {
                        DB::statement("ALTER TABLE api_settings MODIFY {$column} TEXT NULL;");
                    }
                }
            } catch (\Exception $e) {
                // Ignore if driver does not support raw alter
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
