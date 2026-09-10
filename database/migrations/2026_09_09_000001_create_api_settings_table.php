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
        if (!Schema::hasTable('api_settings')) {
            Schema::create('api_settings', function (Blueprint $table) {
                $table->id();

                // 1. Paywuz Gateway
                $table->boolean('paywuz_is_active')->default(true);
                $table->string('paywuz_environment')->default('sandbox');
                $table->string('paywuz_sandbox_api_key')->nullable();
                $table->string('paywuz_production_api_key')->nullable();

                // 2. RajaOngkir Shipping
                $table->boolean('rajaongkir_is_active')->default(true);
                $table->string('rajaongkir_account_type')->default('starter');
                $table->string('rajaongkir_api_key')->nullable();
                $table->string('rajaongkir_origin_city_id')->default('456');

                // 3. Fonnte WhatsApp Gateway
                $table->boolean('fonnte_is_active')->default(true);
                $table->string('fonnte_token')->nullable();
                $table->string('fonnte_country_code')->default('62');

                // 4. FastAPI Chatbot Integration
                $table->boolean('chatbot_is_active')->default(true);
                $table->string('chatbot_url')->default('http://127.0.0.1:8000/chatbot');
                $table->integer('chatbot_timeout')->default(10);

                // 5. Google Gemini AI
                $table->boolean('gemini_is_active')->default(true);
                $table->string('gemini_api_key')->nullable();
                $table->string('gemini_model')->default('gemini-1.5-flash');

                // 6. Google OAuth Socialite
                $table->boolean('google_is_active')->default(true);
                $table->string('google_client_id')->nullable();
                $table->text('google_client_secret')->nullable();
                $table->string('google_redirect_uri')->default('https://becksapparel.com/auth/google/callback');

                $table->timestamps();
            });

            // Seed initial default record safely
            $oldPaymentSetting = Schema::hasTable('payment_settings') ? DB::table('payment_settings')->first() : null;

            DB::table('api_settings')->insert([
                'paywuz_is_active' => $oldPaymentSetting->is_active ?? true,
                'paywuz_environment' => $oldPaymentSetting->environment ?? 'sandbox',
                'paywuz_sandbox_api_key' => $oldPaymentSetting->sandbox_api_key ?? env('PAYWUZ_API_KEY'),
                'paywuz_production_api_key' => $oldPaymentSetting->production_api_key ?? env('PAYWUZ_API_KEY'),
                'rajaongkir_api_key' => env('RAJAONGKIR_API_KEY'),
                'rajaongkir_origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY_ID', '456'),
                'fonnte_token' => env('FONNTE_TOKEN'),
                'chatbot_url' => env('FASTAPI_CHATBOT_URL', 'http://127.0.0.1:8000/chatbot'),
                'gemini_api_key' => env('GEMINI_API_KEY'),
                'google_client_id' => env('GOOGLE_CLIENT_ID'),
                'google_client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'google_redirect_uri' => env('GOOGLE_REDIRECT_URI', 'https://becksapparel.com/auth/google/callback'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_settings');
    }
};
