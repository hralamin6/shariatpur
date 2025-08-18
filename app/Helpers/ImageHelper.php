<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('checkImageUrl')) {
    /**
     * Check if a given URL points to a valid image resource.
     *
     * - Validates URL format
     * - Performs HEAD request to verify reachability and content-type
     * - Falls back to GET when HEAD is not supported
     *
     * @param string|null $url
     * @return bool
     */
    function checkImageUrl(?string $url): bool
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $allowedPrefixes = [
            'image/',              // generic catch-all for images
        ];

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (compatible; ShariatpurCityBot/1.0; +https://example.com/bot)',
            'Accept' => 'image/*,*/*;q=0.8',
        ];

        try {
            $response = Http::timeout(7)->withHeaders($headers)->head($url);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type');
                $contentLength = (int) ($response->header('Content-Length') ?? 0);

                if ($contentType && starts_with_any($contentType, $allowedPrefixes) && $contentLength >= 0) {
                    return true;
                }
            }

            // Some servers don't support HEAD; try a lightweight GET
            $response = Http::timeout(10)->withHeaders($headers)->get($url);
            if (!$response->successful()) {
                return false;
            }

            $contentType = $response->header('Content-Type');
            if ($contentType && starts_with_any($contentType, $allowedPrefixes)) {
                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }

        return false;
    }
}

if (!function_exists('starts_with_any')) {
    /**
     * Helper: does $haystack start with any of the prefixes provided?
     */
    function starts_with_any(?string $haystack, array $prefixes): bool
    {
        if ($haystack === null) return false;
        foreach ($prefixes as $prefix) {
            if (strncasecmp($haystack, $prefix, strlen($prefix)) === 0) {
                return true;
            }
        }
        return false;
    }
}

