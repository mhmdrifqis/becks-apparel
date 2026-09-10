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
        Schema::table('api_settings', function (Blueprint $table) {
            // 7. Biteship Logistics API
            if (!Schema::hasColumn('api_settings', 'biteship_is_active')) {
                $table->boolean('biteship_is_active')->default(false);
            }
            if (!Schema::hasColumn('api_settings', 'biteship_api_key')) {
                $table->text('biteship_api_key')->nullable();
            }
            if (!Schema::hasColumn('api_settings', 'biteship_origin_postal_code')) {
                $table->string('biteship_origin_postal_code')->nullable();
            }

            // 8. SMTP Email Gateway
            if (!Schema::hasColumn('api_settings', 'smtp_is_active')) {
                $table->boolean('smtp_is_active')->default(true);
            }
            if (!Schema::hasColumn('api_settings', 'smtp_host')) {
                $table->string('smtp_host')->nullable()->default('mail.becksapparel.com');
            }
            if (!Schema::hasColumn('api_settings', 'smtp_port')) {
                $table->integer('smtp_port')->default(465);
            }
            if (!Schema::hasColumn('api_settings', 'smtp_username')) {
                $table->string('smtp_username')->nullable();
            }
            if (!Schema::hasColumn('api_settings', 'smtp_password')) {
                $table->text('smtp_password')->nullable();
            }
            if (!Schema::hasColumn('api_settings', 'smtp_encryption')) {
                $table->string('smtp_encryption')->default('ssl');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_settings', function (Blueprint $table) {
            $table->dropColumn([
                'biteship_is_active',
                'biteship_api_key',
                'biteship_origin_postal_code',
                'smtp_is_active',
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'smtp_encryption',
            ]);
        });
    }
};
