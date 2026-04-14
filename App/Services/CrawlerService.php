<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CrawlerService
{
    public function crawl($url)
    {
        try {
            $response = Http::timeout(15)->get($url);
            $html = $response->body();

            preg_match_all('/href=["\'](.*?)["\']/', $html, $matches);

            $links = [];

            foreach ($matches[1] as $link) {
                // skip anchor & javascript
                if (str_starts_with($link, '#') || str_starts_with($link, 'javascript')) {
                    continue;
                }

                // ubah relative URL jadi absolute
                if (!str_starts_with($link, 'http')) {
                    $link = rtrim($url, '/') . '/' . ltrim($link, '/');
                }

                $links[] = $link;
            }

            return array_unique($links);

        } catch (\Exception $e) {
            return [];
        }
    }
}
