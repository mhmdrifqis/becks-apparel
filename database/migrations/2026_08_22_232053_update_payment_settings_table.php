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
                if (Schema::hasColumn('payment_settings', 'midtrans_server_key')) {
                    $columnsToDrop[] = 'midtrans_server_key';
                }
                if (Schema::hasColumn('payment_settings', 'midtrans_client_key')) {
                    $columnsToDrop[] = 'midtrans_client_key';
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
                if (!Schema::hasColumn('payment_settings', 'api_key')) {
                    $table->text('api_key')->nullable()->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_settings')) {
            Schema::table('payment_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_settings', 'midtrans_server_key')) {
                    $table->text('midtrans_server_key')->nullable();
                }
                if (!Schema::hasColumn('payment_settings', 'midtrans_client_key')) {
                    $table->text('midtrans_client_key')->nullable();
                }
                if (Schema::hasColumn('payment_settings', 'api_key')) {
                    $table->dropColumn('api_key');
                }
            });
        }
    }
};
