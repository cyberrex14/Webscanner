<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Scan;
use App\Models\Result;

class ScannerService
{
    public function scan($url)
    {
        // simpan scan awal
        $scan = Scan::create([
            'url' => $url,
            'status' => 'pending'
        ]);

        $payload = "<script>alert(1)</script>";
        $testUrl = $url . "?q=" . urlencode($payload);

        try {
            $response = Http::timeout(15)->get($testUrl);
            $body = $response->body();

            $isVulnerable = str_contains($body, $payload);

            // simpan hasil
            Result::create([
                'scan_id' => $scan->id,
                'type' => 'XSS',
                'is_vulnerable' => $isVulnerable,
                'payload' => $payload
            ]);

            // update status scan
            $scan->update([
                'status' => 'done'
            ]);

            return [
                "status" => "completed",
                "scan_id" => $scan->id,
                "vulnerable" => $isVulnerable
            ];

        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }
    }
}
