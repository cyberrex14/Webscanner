<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ScannerService
{
    public function scan($url)
    {
        $payload = "<script>alert(1)</script>";

        // inject payload ke URL
        $testUrl = $url . "?q=" . urlencode($payload);

        try {
            $response = Http::timeout(15)->get($testUrl);
            $body = $response->body();

            if (str_contains($body, $payload)) {
                return [
                    "status" => "vulnerable",
                    "type" => "XSS",
                    "target" => $url
                ];
            } else {
                return [
                    "status" => "safe",
                    "type" => "XSS",
                    "target" => $url
                ];
            }

        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }
    }
}
