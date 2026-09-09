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
        $recipient = \App\Helpers\PhoneHelper::normalize($recipient);

        if (empty($recipient)) {
            Log::warning('Fonnte WhatsApp: Nomor penerima kosong atau tidak valid.');
            return ['status' => false, 'reason' => 'Empty recipient phone number'];
        }

        if (empty($this->token)) {
            Log::warning('Fonnte Token tidak ditemukan. Pesan WA tidak terkirim.');
            return ['status' => false, 'reason' => 'No Token'];
        }

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $this->token,
                ])->post($this->baseUrl, [
                    'target' => $recipient,
                    'message' => $message,
                    'countryCode' => '62', // Default Indonesia
                ]);

            $result = $response->json();
            
            if (!$response->successful() || (isset($result['status']) && $result['status'] === false)) {
                Log::error("Fonnte API Warning/Error for target ({$recipient}): " . json_encode($result));
            } else {
                Log::info("Fonnte API Success for target ({$recipient}): " . json_encode($result));
            }

            return is_array($result) ? $result : ['status' => false, 'reason' => 'Invalid Response'];

        } catch (\Exception $e) {
            Log::error("WhatsApp Service Exception for target ({$recipient}): " . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }
}
