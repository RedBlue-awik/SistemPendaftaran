<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    /**
     * Send a WhatsApp text message using configured API (WhatsApp Cloud API compatible).
     * Expects env: WA_API_TOKEN, WA_PHONE_NUMBER_ID, WA_API_BASE (optional, default https://graph.facebook.com)
     */
    public static function send(string $to, string $message)
    {
        $provider = env('WA_PROVIDER', 'meta');

        // allow forcing a test destination for development
        $testTo = env('WA_FONNTE_TEST_TO');
        if (!empty($testTo)) {
            $to = $testTo;
        }

        if ($provider === 'fonnte') {
            $token = env('WA_API_TOKEN');
            $url = rtrim(env('WA_FONNTE_API_URL', 'https://api.fonnte.id'), '/');

            if (empty($token) || empty($to)) return false;

            $payload = [
                'to' => $to,
                'message' => $message,
            ];

            $resp = Http::withToken($token)
                ->post($url, $payload);

            return $resp->successful();
        }

        // default: Meta / WhatsApp Cloud API
        $token = env('WA_API_TOKEN');
        $phoneId = env('WA_PHONE_NUMBER_ID');
        $base = env('WA_API_BASE', 'https://graph.facebook.com');

        if (empty($token) || empty($phoneId) || empty($to)) {
            return false;
        }

        $url = rtrim($base, '/') . '/' . $phoneId . '/messages';

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'body' => $message,
            ],
        ];

        $resp = Http::withToken($token)
            ->post($url, $payload);

        return $resp->successful();
    }
}
