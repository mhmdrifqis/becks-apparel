<?php

namespace App\Helpers;

use App\Models\ApiSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ApiSettingHelper
{
    /**
     * Bind dynamic database API settings into Laravel runtime config()
     */
    public static function loadIntoConfig(): void
    {
        try {
            if (!Schema::hasTable('api_settings')) {
                return;
            }

            $setting = ApiSetting::instance();

            // 1. Paywuz
            $paywuzKey = $setting->getActivePaywuzApiKey();
            if ($paywuzKey) {
                config(['services.paywuz.api_key' => $paywuzKey]);
            }
            config(['services.paywuz.is_active' => $setting->paywuz_is_active]);
            config(['services.paywuz.is_production' => $setting->paywuz_environment === 'production']);

            // 2. RajaOngkir
            if ($setting->rajaongkir_api_key) {
                config(['services.rajaongkir.api_key' => $setting->rajaongkir_api_key]);
            }
            if ($setting->rajaongkir_origin_city_id) {
                config(['services.rajaongkir.origin_city_id' => $setting->rajaongkir_origin_city_id]);
            }
            config(['services.rajaongkir.account_type' => $setting->rajaongkir_account_type]);
            config(['services.rajaongkir.is_active' => $setting->rajaongkir_is_active]);

            // 3. Fonnte WhatsApp
            if ($setting->fonnte_token) {
                config(['services.fonnte.token' => $setting->fonnte_token]);
            }
            config(['services.fonnte.country_code' => $setting->fonnte_country_code]);
            config(['services.fonnte.is_active' => $setting->fonnte_is_active]);

            // 4. FastAPI Chatbot
            if ($setting->chatbot_url) {
                config(['services.chatbot.url' => $setting->chatbot_url]);
            }
            config(['services.chatbot.timeout' => $setting->chatbot_timeout]);
            config(['services.chatbot.is_active' => $setting->chatbot_is_active]);

            // 5. Google Gemini AI
            if ($setting->gemini_api_key) {
                config(['services.gemini.key' => $setting->gemini_api_key]);
            }
            config(['services.gemini.model' => $setting->gemini_model]);
            config(['services.gemini.is_active' => $setting->gemini_is_active]);

            // 6. Google OAuth
            if ($setting->google_client_id) {
                config(['services.google.client_id' => $setting->google_client_id]);
            }
            if ($setting->google_client_secret) {
                config(['services.google.client_secret' => $setting->google_client_secret]);
            }
            if ($setting->google_redirect_uri) {
                config(['services.google.redirect' => $setting->google_redirect_uri]);
            }
            config(['services.google.is_active' => $setting->google_is_active]);

            // 7. Biteship Logistics
            if ($setting->biteship_api_key) {
                config(['services.biteship.api_key' => $setting->biteship_api_key]);
            }
            config(['services.biteship.is_active' => $setting->biteship_is_active]);

            // 8. SMTP Email Gateway
            if ($setting->smtp_host) {
                config(['mail.mailers.smtp.host' => $setting->smtp_host]);
            }
            if ($setting->smtp_port) {
                config(['mail.mailers.smtp.port' => (int) $setting->smtp_port]);
            }
            if ($setting->smtp_username) {
                config(['mail.mailers.smtp.username' => $setting->smtp_username]);
            }
            if ($setting->smtp_password) {
                config(['mail.mailers.smtp.password' => $setting->smtp_password]);
            }
            if ($setting->smtp_encryption) {
                config(['mail.mailers.smtp.encryption' => $setting->smtp_encryption]);
            }
            if ($setting->smtp_from_address) {
                config(['mail.from.address' => $setting->smtp_from_address]);
            }
            if ($setting->smtp_from_name) {
                config(['mail.from.name' => $setting->smtp_from_name]);
            }
            config(['services.smtp.is_active' => $setting->smtp_is_active]);

        } catch (\Exception $e) {
            Log::warning("ApiSettingHelper: Failed to load dynamic API settings - " . $e->getMessage());
        }
    }
}
