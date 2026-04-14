<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Scan;
use App\Models\Result;
use App\Models\Vulnerability;
use App\Services\CrawlerService;

class ScannerService
{
    protected $crawler;

    public function __construct(CrawlerService $crawler)
    {
        $this->crawler = $crawler;
    }

    public function scan($url)
    {
        // buat scan
        $scan = Scan::create([
            'url' => $url,
            'status' => 'pending'
        ]);

        // 🔥 ambil semua link dari crawler
        $links = $this->crawler->crawl($url);

        // batasi biar ga berat (max 5 link)
        $links = array_slice($links, 0, 5);

        // kalau crawler kosong, tetap scan URL utama
        if (empty($links)) {
            $links = [$url];
        }

        // 🔥 loop semua link
        foreach ($links as $link) {
            $this->scanXSS($link, $scan->id);
            $this->scanSQLi($link, $scan->id);
        }

        // update status
        $scan->update([
            'status' => 'done'
        ]);

        return [
            "status" => "completed",
            "scan_id" => $scan->id,
            "links_scanned" => $links
        ];
    }

    // 🔴 XSS SCAN
    private function scanXSS($url, $scanId)
    {
        $payload = "<script>alert(1)</script>";
        $testUrl = $url . "?q=" . urlencode($payload);

        try {
            $response = Http::timeout(15)->get($testUrl);
            $body = $response->body();

            $isVulnerable = str_contains($body, $payload);

            $result = Result::create([
                'scan_id' => $scanId,
                'type' => 'XSS',
                'is_vulnerable' => $isVulnerable,
                'payload' => $payload
            ]);

            if ($isVulnerable) {
                Vulnerability::create([
                    'result_id' => $result->id,
                    'name' => 'Cross Site Scripting',
                    'severity' => 'high',
                    'description' => 'Reflected XSS detected'
                ]);
            }

        } catch (\Exception $e) {
            // silent error
        }
    }

    // 🔥 SQL INJECTION SCAN
    private function scanSQLi($url, $scanId)
    {
        $payload = "' OR 1=1 --";
        $testUrl = $url . "?id=" . urlencode($payload);

        try {
            $response = Http::timeout(15)->get($testUrl);
            $body = strtolower($response->body());

            $errors = [
                "sql syntax",
                "mysql",
                "syntax error",
                "warning",
                "pdo",
                "query failed"
            ];

            $isVulnerable = false;

            foreach ($errors as $error) {
                if (str_contains($body, $error)) {
                    $isVulnerable = true;
                    break;
                }
            }

            $result = Result::create([
                'scan_id' => $scanId,
                'type' => 'SQLi',
                'is_vulnerable' => $isVulnerable,
                'payload' => $payload
            ]);

            if ($isVulnerable) {
                Vulnerability::create([
                    'result_id' => $result->id,
                    'name' => 'SQL Injection',
                    'severity' => 'high',
                    'description' => 'Possible SQL Injection vulnerability'
                ]);
            }

        } catch (\Exception $e) {
            // silent error
        }
    }
}
