<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function send(string $to, string $pesan)
    {
        $provider = env('WA_PROVIDER', 'fonnte');

        $to = preg_replace('/^0/', '62', $to);

        Log::info('Mengirim WA', [
            'target' => $to,
            'message' => $pesan
        ]);

        if ($provider === 'fonnte') {

            $token = env('WA_API_TOKEN');
            $url = env('WA_FONNTE_API_URL', 'https://api.fonnte.com/send');

            $payload = [
                'target' => $to,
                'message' => $pesan,
            ];

            $resp = Http::withHeaders([
                'Authorization' => $token
            ])
            ->timeout(20)
            ->post($url, $payload);

            Log::info('Fonnte response', [
                'status' => $resp->status(),
                'body' => $resp->body()
            ]);

            return $resp->successful();
        }

        return false;
    }
}
