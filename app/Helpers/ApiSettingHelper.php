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
            if (Schema::hasColumn('api_settings', 'paywuz_is_active')) {
                $paywuzKey = $setting->getActivePaywuzApiKey();
                if ($paywuzKey) {
                    config(['services.paywuz.api_key' => $paywuzKey]);
                }
                config(['services.paywuz.is_active' => (bool) ($setting->paywuz_is_active ?? true)]);
                config(['services.paywuz.is_production' => ($setting->paywuz_environment ?? 'sandbox') === 'production']);
            }

            // 2. RajaOngkir
            if (Schema::hasColumn('api_settings', 'rajaongkir_is_active')) {
                if (!empty($setting->rajaongkir_api_key)) {
                    config(['services.rajaongkir.api_key' => $setting->rajaongkir_api_key]);
                }
                if (!empty($setting->rajaongkir_origin_city_id)) {
                    config(['services.rajaongkir.origin_city_id' => $setting->rajaongkir_origin_city_id]);
                }
                config(['services.rajaongkir.account_type' => $setting->rajaongkir_account_type ?? 'starter']);
                config(['services.rajaongkir.is_active' => (bool) ($setting->rajaongkir_is_active ?? true)]);
            }

            // 3. Fonnte WhatsApp
            if (Schema::hasColumn('api_settings', 'fonnte_is_active')) {
                if (!empty($setting->fonnte_token)) {
                    config(['services.fonnte.token' => $setting->fonnte_token]);
                }
                config(['services.fonnte.country_code' => $setting->fonnte_country_code ?? '62']);
                config(['services.fonnte.is_active' => (bool) ($setting->fonnte_is_active ?? true)]);
            }

            // 4. FastAPI Chatbot
            if (Schema::hasColumn('api_settings', 'chatbot_is_active')) {
                if (!empty($setting->chatbot_url)) {
                    config(['services.chatbot.url' => $setting->chatbot_url]);
                }
                config(['services.chatbot.timeout' => $setting->chatbot_timeout ?? 10]);
                config(['services.chatbot.is_active' => (bool) ($setting->chatbot_is_active ?? true)]);
            }

            // 5. Google Gemini AI
            if (Schema::hasColumn('api_settings', 'gemini_is_active')) {
                if (!empty($setting->gemini_api_key)) {
                    config(['services.gemini.key' => $setting->gemini_api_key]);
                }
                config(['services.gemini.model' => $setting->gemini_model ?? 'gemini-1.5-flash']);
                config(['services.gemini.is_active' => (bool) ($setting->gemini_is_active ?? true)]);
            }

            // 6. Google OAuth
            if (Schema::hasColumn('api_settings', 'google_is_active')) {
                if (!empty($setting->google_client_id)) {
                    config(['services.google.client_id' => $setting->google_client_id]);
                }
                if (!empty($setting->google_client_secret)) {
                    config(['services.google.client_secret' => $setting->google_client_secret]);
                }
                if (!empty($setting->google_redirect_uri)) {
                    config(['services.google.redirect' => $setting->google_redirect_uri]);
                }
                config(['services.google.is_active' => (bool) ($setting->google_is_active ?? true)]);
            }

            // 7. Biteship Logistics
            if (Schema::hasColumn('api_settings', 'biteship_is_active')) {
                if (!empty($setting->biteship_api_key)) {
                    config(['services.biteship.api_key' => $setting->biteship_api_key]);
                }
                config(['services.biteship.is_active' => (bool) ($setting->biteship_is_active ?? false)]);
            }

            // 8. SMTP Email Gateway
            if (Schema::hasColumn('api_settings', 'smtp_is_active')) {
                if (!empty($setting->smtp_host)) {
                    config(['mail.mailers.smtp.host' => $setting->smtp_host]);
                }
                if (!empty($setting->smtp_port)) {
                    config(['mail.mailers.smtp.port' => (int) $setting->smtp_port]);
                }
                if (!empty($setting->smtp_username)) {
                    config(['mail.mailers.smtp.username' => $setting->smtp_username]);
                }
                if (!empty($setting->smtp_password)) {
                    config(['mail.mailers.smtp.password' => $setting->smtp_password]);
                }
                if (!empty($setting->smtp_encryption)) {
                    config(['mail.mailers.smtp.encryption' => $setting->smtp_encryption]);
                }
                if (!empty($setting->smtp_from_address)) {
                    config(['mail.from.address' => $setting->smtp_from_address]);
                }
                if (!empty($setting->smtp_from_name)) {
                    config(['mail.from.name' => $setting->smtp_from_name]);
                }
                config(['services.smtp.is_active' => (bool) ($setting->smtp_is_active ?? true)]);
            }

        } catch (\Exception $e) {
            Log::warning("ApiSettingHelper: Failed to load dynamic API settings - " . $e->getMessage());
        }
    }
}
