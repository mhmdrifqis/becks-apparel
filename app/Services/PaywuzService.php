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
        $this->apiKey = config('services.paywuz.api_key', '');
        // Menggunakan v1 sesuai docs
        $this->baseUrl = 'https://api.paywuz.id/v1'; 
    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function client()
    {
        return Http::withHeaders([
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
