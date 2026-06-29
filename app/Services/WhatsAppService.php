<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $baseUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', env('FONNTE_TOKEN', ''));
    }

    /**
     * Kirim pesan WhatsApp menggunakan Fonnte
     *
     * @param string $recipient Nomor target (misal: 08123456789 atau 628123456789)
     * @param string $message Isi pesan
     * @return array
     */
    public function sendMessage(string $recipient, string $message): array
    {
        if (empty($this->token)) {
            Log::warning('Fonnte Token tidak ditemukan. Pesan WA tidak terkirim.');
            return ['status' => false, 'reason' => 'No Token'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl, [
                'target' => $recipient,
                'message' => $message,
                'countryCode' => '62', // Default Indonesia
            ]);

            $result = $response->json();
            
            if (!$response->successful()) {
                Log::error('Fonnte API Error: ' . json_encode($result));
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('WhatsApp Service Exception: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }
}
