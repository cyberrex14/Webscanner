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

    /**
     * Main scan entry
     */
    public function scan($url): array
    {
        $results = [];

        try {
            $links = $this->crawler->crawl($url);

            if (!is_array($links)) {
                $links = [];
            }

        } catch (\Throwable $e) {
            Log::warning('Crawler failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            $links = [];
        }

        // limit scan
        $links = array_slice($links, 0, 5);

        if (empty($links)) {
            $links = [$url];
        }

        foreach ($links as $link) {

            $xss = $this->scanXSS($link);
            if ($xss !== null) {
                $results[] = $xss;
            }

            $sqli = $this->scanSQLi($link);
            if ($sqli !== null) {
                $results[] = $sqli;
            }
        }

        return $results;
    }

    /**
     * 🔴 XSS SCAN
     */
    private function scanXSS($url): ?array
    {
        $payload = "<script>alert(1)</script>";
        $testUrl = $url . "?q=" . urlencode($payload);

        try {
            $response = Http::timeout(10)
                ->retry(1, 100)
                ->get($testUrl);

            if (!$response->ok()) {
                return null;
            }

            $body = $response->body();

            if (str_contains($body, $payload)) {
                return [
                    'type' => 'XSS',
                    'payload' => $payload,
                    'url' => $url,
                    'severity' => 'high',
                    'description' => "Reflected XSS detected on {$url}",
                ];
            }

        } catch (\Throwable $e) {
            Log::warning('XSS scan error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * 🔥 SQL INJECTION SCAN
     */
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
                "query failed",
            ];

            foreach ($errors as $error) {
                if (str_contains($body, $error)) {
                    return [
                        'type' => 'SQLi',
                        'payload' => $payload,
                        'url' => $url,
                        'severity' => 'high',
                        'description' => "Possible SQL Injection detected on {$url}",
                    ];
                }
            }

        } catch (\Throwable $e) {
            Log::warning('SQLi scan error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
