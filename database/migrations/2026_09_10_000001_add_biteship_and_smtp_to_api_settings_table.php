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
            $table->boolean('biteship_is_active')->default(false)->after('google_redirect_uri');
            $table->string('biteship_api_key')->nullable()->after('biteship_is_active');
            $table->string('biteship_origin_postal_code')->nullable()->after('biteship_api_key');

            // 8. SMTP Email Gateway
            $table->boolean('smtp_is_active')->default(true)->after('biteship_origin_postal_code');
            $table->string('smtp_host')->nullable()->default('mail.becksapparel.com')->after('smtp_is_active');
            $table->integer('smtp_port')->default(465)->after('smtp_host');
            $table->string('smtp_username')->nullable()->after('smtp_port');
            $table->text('smtp_password')->nullable()->after('smtp_username');
            $table->string('smtp_encryption')->default('ssl')->after('smtp_password');
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
