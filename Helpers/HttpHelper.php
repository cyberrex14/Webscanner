<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class HttpHelper
{
    /**
     * GET request aman
     */
    public static function get(string $url, array $headers = []): array
    {
        self::guardUrl($url);

        $response = Http::timeout(10)
            ->withHeaders($headers)
            ->get($url);

        return [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers(),
        ];
    }

    /**
     * POST request
     */
    public static function post(string $url, array $data = [], array $headers = []): array
    {
        self::guardUrl($url);

        $response = Http::timeout(10)
            ->withHeaders($headers)
            ->post($url, $data);

        return [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers(),
        ];
    }

    /**
     * Basic SSRF guard (minimal)
     */
    protected static function guardUrl(string $url): void
    {
        $parsed = parse_url($url);

        if (!isset($parsed['scheme']) || !in_array(strtolower($parsed['scheme']), ['http', 'https'])) {
            throw new InvalidUrlSchemeException('Invalid URL scheme');
        }

        // blok localhost / internal (basic)
        if (isset($parsed['host'])) {
            $host = $parsed['host'];

            if (in_array($host, ['localhost', '127.0.0.1'])) {
                throw new BlockedInternalAddressException('Blocked internal address');
            }
        }
        
        class InvalidUrlSchemeException extends \InvalidArgumentException
        {
        }
        
        class BlockedInternalAddressException extends \Exception
        {
        }
    }
}
