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
     * Singleton instance helper
     */
    public static function instance(): self
    {
        try {
            $setting = static::first();
            if ($setting) {
                return $setting;
            }

            return static::create([
                'paywuz_is_active' => true,
                'paywuz_environment' => 'sandbox',
                'rajaongkir_is_active' => true,
                'rajaongkir_account_type' => 'starter',
                'rajaongkir_origin_city_id' => '456',
                'fonnte_is_active' => true,
                'fonnte_country_code' => '62',
                'chatbot_is_active' => true,
                'chatbot_url' => 'http://127.0.0.1:8000/chatbot',
                'chatbot_timeout' => 10,
                'gemini_is_active' => true,
                'gemini_model' => 'gemini-1.5-flash',
                'google_is_active' => true,
                'google_redirect_uri' => 'https://becksapparel.com/auth/google/callback',
            ]);
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
