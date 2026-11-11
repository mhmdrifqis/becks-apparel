<?php

namespace Database\Factories;

use App\Models\ApiSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApiSettingFactory extends Factory
{
    protected $model = ApiSetting::class;

    public function definition(): array
    {
        return [
            'paywuz_is_active' => true,
            'paywuz_environment' => 'sandbox',
            'paywuz_sandbox_api_key' => 'sand_test_key_123',
            'paywuz_production_api_key' => 'prod_test_key_123',
            'rajaongkir_is_active' => true,
            'rajaongkir_account_type' => 'starter',
            'rajaongkir_api_key' => 'rajaongkir_test_key',
            'rajaongkir_origin_city_id' => '456',
            'fonnte_is_active' => true,
            'fonnte_token' => 'fonnte_test_token',
            'fonnte_country_code' => '62',
            'chatbot_is_active' => true,
            'chatbot_url' => 'http://127.0.0.1:8000/chatbot',
            'chatbot_timeout' => 10,
            'gemini_is_active' => true,
            'gemini_api_key' => 'gemini_test_api_key',
            'gemini_model' => 'gemini-1.5-flash',
            'google_is_active' => true,
            'google_client_id' => 'google_client_id_test',
            'google_client_secret' => 'google_secret_test',
            'google_redirect_uri' => 'http://localhost/auth/google/callback',
            'biteship_is_active' => true,
            'biteship_api_key' => 'biteship_test_key',
            'biteship_origin_postal_code' => '15111',
        ];
    }
}
