<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn(['api_key', 'is_production']);
            
            $table->boolean('is_active')->default(true)->after('id');
            $table->string('environment')->default('sandbox')->after('is_active');
            $table->text('sandbox_api_key')->nullable()->after('environment');
            $table->text('production_api_key')->nullable()->after('sandbox_api_key');
        });
    }

    public function down(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'environment', 'sandbox_api_key', 'production_api_key']);
            $table->text('api_key')->nullable();
            $table->boolean('is_production')->default(false);
        });
    }
};
