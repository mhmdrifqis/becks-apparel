<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSetting extends Model
{
    use HasFactory;

    protected $table = 'api_settings';

    protected $fillable = [
        // Paywuz
        'paywuz_is_active',
        'paywuz_environment',
        'paywuz_sandbox_api_key',
        'paywuz_production_api_key',

        // RajaOngkir
        'rajaongkir_is_active',
        'rajaongkir_account_type',
        'rajaongkir_api_key',
        'rajaongkir_origin_city_id',

        // Fonnte
        'fonnte_is_active',
        'fonnte_token',
        'fonnte_country_code',

        // Chatbot
        'chatbot_is_active',
        'chatbot_url',
        'chatbot_timeout',

        // Gemini AI
        'gemini_is_active',
        'gemini_api_key',
        'gemini_model',

        // Google OAuth
        'google_is_active',
        'google_client_id',
        'google_client_secret',
        'google_redirect_uri',

        // Biteship Logistics
        'biteship_is_active',
        'biteship_api_key',
        'biteship_origin_postal_code',

        // SMTP Mail Gateway
        'smtp_is_active',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
    ];

    protected $casts = [
        'paywuz_is_active' => 'boolean',
        'rajaongkir_is_active' => 'boolean',
        'fonnte_is_active' => 'boolean',
        'chatbot_is_active' => 'boolean',
        'gemini_is_active' => 'boolean',
        'google_is_active' => 'boolean',
        'biteship_is_active' => 'boolean',
        'smtp_is_active' => 'boolean',
        'chatbot_timeout' => 'integer',
        'smtp_port' => 'integer',
    ];

    protected $hidden = [
        'paywuz_sandbox_api_key',
        'paywuz_production_api_key',
        'rajaongkir_api_key',
        'fonnte_token',
        'gemini_api_key',
        'google_client_secret',
        'biteship_api_key',
        'smtp_password',
    ];

    /**
     * Helper to safely decrypt secret values
     */
    protected function decryptSecret($value)
    {
        if (empty($value)) {
            return $value;
        }
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Fallback to raw value if not encrypted yet
        }
    }

    /**
     * Helper to safely encrypt secret values
     */
    protected function encryptSecret($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            // Only encrypt if it's not already encrypted
            try {
                \Illuminate\Support\Facades\Crypt::decryptString($value);
                return $value; // Already encrypted
            } catch (\Exception $e) {
                return \Illuminate\Support\Facades\Crypt::encryptString($value);
            }
        } catch (\Exception $e) {
            return $value;
        }
    }

    // Accessors & Mutators for all 8 sensitive credentials
    public function getPaywuzSandboxApiKeyAttribute($v) { return $this->decryptSecret($v); }
    public function setPaywuzSandboxApiKeyAttribute($v) { $this->attributes['paywuz_sandbox_api_key'] = $this->encryptSecret($v); }

    public function getPaywuzProductionApiKeyAttribute($v) { return $this->decryptSecret($v); }
    public function setPaywuzProductionApiKeyAttribute($v) { $this->attributes['paywuz_production_api_key'] = $this->encryptSecret($v); }

    public function getRajaongkirApiKeyAttribute($v) { return $this->decryptSecret($v); }
    public function setRajaongkirApiKeyAttribute($v) { $this->attributes['rajaongkir_api_key'] = $this->encryptSecret($v); }

    public function getFonnteTokenAttribute($v) { return $this->decryptSecret($v); }
    public function setFonnteTokenAttribute($v) { $this->attributes['fonnte_token'] = $this->encryptSecret($v); }

    public function getGeminiApiKeyAttribute($v) { return $this->decryptSecret($v); }
    public function setGeminiApiKeyAttribute($v) { $this->attributes['gemini_api_key'] = $this->encryptSecret($v); }

    public function getGoogleClientSecretAttribute($v) { return $this->decryptSecret($v); }
    public function setGoogleClientSecretAttribute($v) { $this->attributes['google_client_secret'] = $this->encryptSecret($v); }

    public function getBiteshipApiKeyAttribute($v) { return $this->decryptSecret($v); }
    public function setBiteshipApiKeyAttribute($v) { $this->attributes['biteship_api_key'] = $this->encryptSecret($v); }

    public function getSmtpPasswordAttribute($v) { return $this->decryptSecret($v); }
    public function setSmtpPasswordAttribute($v) { $this->attributes['smtp_password'] = $this->encryptSecret($v); }

    /**
     * Singleton instance helper with automatic env/config hydration
     */
    public static function instance(): self
    {
        try {
            $setting = static::orderBy('id', 'desc')->first();
            if (!$setting) {
                $setting = new static();
            }

            $dirty = false;

            // Hydrate empty fields from env/config so nothing is lost
            if (empty($setting->paywuz_sandbox_api_key) && env('PAYWUZ_API_KEY')) {
                $setting->paywuz_sandbox_api_key = env('PAYWUZ_API_KEY');
                $dirty = true;
            }
            if (empty($setting->paywuz_production_api_key) && env('PAYWUZ_API_KEY')) {
                $setting->paywuz_production_api_key = env('PAYWUZ_API_KEY');
                $dirty = true;
            }
            if (empty($setting->rajaongkir_api_key) && env('RAJAONGKIR_API_KEY')) {
                $setting->rajaongkir_api_key = env('RAJAONGKIR_API_KEY');
                $dirty = true;
            }
            if (empty($setting->rajaongkir_origin_city_id) && env('RAJAONGKIR_ORIGIN_CITY_ID')) {
                $setting->rajaongkir_origin_city_id = env('RAJAONGKIR_ORIGIN_CITY_ID', '456');
                $dirty = true;
            }
            if (empty($setting->fonnte_token) && env('FONNTE_TOKEN')) {
                $setting->fonnte_token = env('FONNTE_TOKEN');
                $dirty = true;
            }
            if (empty($setting->chatbot_url) && env('FASTAPI_CHATBOT_URL')) {
                $setting->chatbot_url = env('FASTAPI_CHATBOT_URL', 'http://127.0.0.1:8000/chatbot');
                $dirty = true;
            }
            if (empty($setting->gemini_api_key) && env('GEMINI_API_KEY')) {
                $setting->gemini_api_key = env('GEMINI_API_KEY');
                $dirty = true;
            }
            if (empty($setting->google_client_id) && env('GOOGLE_CLIENT_ID')) {
                $setting->google_client_id = env('GOOGLE_CLIENT_ID');
                $dirty = true;
            }
            if (empty($setting->google_client_secret) && env('GOOGLE_CLIENT_SECRET')) {
                $setting->google_client_secret = env('GOOGLE_CLIENT_SECRET');
                $dirty = true;
            }

            if (!$setting->exists) {
                $setting->paywuz_is_active = true;
                $setting->paywuz_environment = 'sandbox';
                $setting->rajaongkir_is_active = true;
                $setting->rajaongkir_account_type = 'starter';
                $setting->fonnte_is_active = true;
                $setting->fonnte_country_code = '62';
                $setting->chatbot_is_active = true;
                $setting->chatbot_timeout = 10;
                $setting->gemini_is_active = true;
                $setting->gemini_model = 'gemini-1.5-flash';
                $setting->google_is_active = true;
                $setting->google_redirect_uri = 'https://becksapparel.com/auth/google/callback';
                $setting->save();
            } elseif ($dirty) {
                $setting->save();
            }

            return $setting;
        } catch (\Exception $e) {
            return new static();
        }
    }

    /**
     * Helper to get active Paywuz API key
     */
    public function getActivePaywuzApiKey(): ?string
    {
        if ($this->paywuz_environment === 'production') {
            return $this->paywuz_production_api_key ?: config('services.paywuz.api_key');
        }
        return $this->paywuz_sandbox_api_key ?: config('services.paywuz.api_key');
    }
}
