<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim Pesan WhatsApp via Fonnte/WooWa (Contoh Implementasi Fonnte)
     * 
     * @param string $phone Nomor HP tujuan (format: 08xx atau 62xx)
     * @param string $message Pesan yang akan dikirim
     * @return bool|string True jika sukses, error message jika gagal
     */
    public static function send($phone, $message)
    {
        $apiKey = env('WA_API_KEY'); // Masukkan API KEY Anda di .env

        if (!$apiKey) {
            Log::warning('WhatsApp API Key belum disetting di .env');
            return false;
        }

        try {
            // Contoh endpoint Fonnte
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Otomatis ubah 08 jadi 62
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WA Error: ' . $response->body());
            return $response->body();

        } catch (\Exception $e) {
            Log::error('WA Exception: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}
