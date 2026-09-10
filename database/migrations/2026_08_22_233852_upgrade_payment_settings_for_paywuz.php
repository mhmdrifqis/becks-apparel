<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payment_settings')) {
            Schema::table('payment_settings', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('payment_settings', 'api_key')) {
                    $columnsToDrop[] = 'api_key';
                }
                if (Schema::hasColumn('payment_settings', 'is_production')) {
                    $columnsToDrop[] = 'is_production';
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }

                if (!Schema::hasColumn('payment_settings', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('id');
                }
                if (!Schema::hasColumn('payment_settings', 'environment')) {
                    $table->string('environment')->default('sandbox')->after('is_active');
                }
                if (!Schema::hasColumn('payment_settings', 'sandbox_api_key')) {
                    $table->text('sandbox_api_key')->nullable()->after('environment');
                }
                if (!Schema::hasColumn('payment_settings', 'production_api_key')) {
                    $table->text('production_api_key')->nullable()->after('sandbox_api_key');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_settings')) {
            Schema::table('payment_settings', function (Blueprint $table) {
                $columnsToDrop = [];
                foreach (['is_active', 'environment', 'sandbox_api_key', 'production_api_key'] as $col) {
                    if (Schema::hasColumn('payment_settings', $col)) {
                        $columnsToDrop[] = $col;
                    }
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }

                if (!Schema::hasColumn('payment_settings', 'api_key')) {
                    $table->text('api_key')->nullable();
                }
                if (!Schema::hasColumn('payment_settings', 'is_production')) {
                    $table->boolean('is_production')->default(false);
                }
            });
        }
    }
};
