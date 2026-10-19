<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaywuzService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://api.paywuz.id/v1';
        $apiSetting = \App\Models\ApiSetting::first();
        if ($apiSetting) {
            $isSandbox = ($apiSetting->paywuz_environment ?? 'sandbox') === 'sandbox';
            $key = $isSandbox ? $apiSetting->paywuz_sandbox_api_key : $apiSetting->paywuz_production_api_key;
            if (empty($key)) {
                $key = $isSandbox ? config('services.paywuz.sandbox_api_key') : config('services.paywuz.production_api_key');
            }
            $this->apiKey = $key ?: config('services.paywuz.api_key', '');
        } else {
            $isProduction = config('services.paywuz.is_production', false);
            $key = $isProduction ? config('services.paywuz.production_api_key') : config('services.paywuz.sandbox_api_key');
            $this->apiKey = $key ?: config('services.paywuz.api_key', '');
        }
    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function client()
    {
        return Http::timeout(15)->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ]);
    }

    /**
     * Buat transaksi pembayaran baru
     */
    public function createTransaction(array $payload)
    {
        try {
            // Endpoint umum pembuatan transaksi
            $response = $this->client()->post("{$this->baseUrl}/transactions", $payload);
            
            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Paywuz Create Transaction Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to create payment: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paywuz Create Transaction Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cek status transaksi via API
     */
    public function getTransactionStatus(string $transactionId)
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/transactions/{$transactionId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Paywuz Get Status Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Paywuz Get Status Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifikasi validitas webhook/callback
     */
    public function verifyCallbackSignature($request)
    {
        // TODO: Sesuaikan dengan metode security Paywuz (misal mengecek header signature)
        // Saat ini default true untuk pengujian awal.
        return true; 
    }
}
