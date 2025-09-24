<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InstagramPostService
{

    private function baseUrl()
    {
        return env("APP_AFFILIATE_URL", 'https://partner.baaboo.com') . '/api';
    }


    public function getPostById(int $id): ?array
    {
        $url = "{$this->baseUrl()}/instagram-post/{$id}";

        $response = Http::withoutVerifying()->get($url);

        // Check if the request was successful before returning the JSON data.
        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }


    public function getTodayScheduledPosts(): array
    {
        $url = "{$this->baseUrl()}/instagram-posts/get-today-posts";

        $response = Http::withoutVerifying()->get($url);

        // Check for a successful response and that the data is a valid array.
        if ($response->successful() && is_array($response->json())) {
            return $response->json();
        }

        return [];
    }
}
