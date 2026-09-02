<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn(['midtrans_server_key', 'midtrans_client_key']);
            $table->text('api_key')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->text('midtrans_server_key')->nullable();
            $table->text('midtrans_client_key')->nullable();
            $table->dropColumn('api_key');
        });
    }
};
