<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScannerService
{
    protected $crawler;

    public function __construct(CrawlerService $crawler)
    {
        $this->crawler = $crawler;
    }

    public function scan($url): array
    {
        $results = [];

        // 🔥 SAFE CRAWL
        try {
            $links = $this->crawler->crawl($url);

            if (!is_array($links)) {
                $links = [];
            }

        } catch (\Throwable $e) {
            Log::warning('Crawler failed', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            $links = [];
        }

        $links = array_slice($links, 0, 5);

        if (empty($links)) {
            $links = [$url];
        }

        foreach ($links as $link) {

            // 🔴 XSS
            $xss = $this->scanXSS($link);
            if ($xss !== null) {
                $results[] = $xss;
            }

            // 🔥 SQLi
            $sqli = $this->scanSQLi($link);
            if ($sqli !== null) {
                $results[] = $sqli;
            }
        }

        // 🔥 pastikan selalu array
        return is_array($results) ? $results : [];
    }

    private function scanXSS($url): ?array
    {
        $payload = "<script>alert(1)</script>";
        $testUrl = $url . "?q=" . urlencode($payload);

        try {
            $response = Http::timeout(10)
                ->retry(1, 100) // retry 1x
                ->get($testUrl);

            if (!$response->ok()) {
                return null;
            }

            $body = $response->body();

            if (str_contains($body, $payload)) {
                return [
                    'type' => 'XSS',
                    'severity' => 'high',
                    'description' => "Reflected XSS on {$url}"
                ];
            }

        } catch (\Throwable $e) {
            Log::warning('XSS scan error', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    private function scanSQLi($url): ?array
    {
        $payload = "' OR 1=1 --";
        $testUrl = $url . "?id=" . urlencode($payload);

        try {
            $response = Http::timeout(10)
                ->retry(1, 100)
                ->get($testUrl);

            if (!$response->ok()) {
                return null;
            }

            $body = strtolower($response->body());

            $errors = [
                "sql syntax",
                "mysql",
                "syntax error",
                "warning",
                "pdo",
                "query failed"
            ];

            foreach ($errors as $error) {
                if (str_contains($body, $error)) {
                    return [
                        'type' => 'SQLi',
                        'severity' => 'high',
                        'description' => "Possible SQL Injection on {$url}"
                    ];
                }
            }

        } catch (\Throwable $e) {
            Log::warning('SQLi scan error', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
}
