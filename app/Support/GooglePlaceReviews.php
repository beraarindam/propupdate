<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

final class GooglePlaceReviews
{
    private const CACHE_TTL_SECONDS = 3600;

    public static function cacheKey(int $siteSettingsId): string
    {
        return 'google_place_reviews:'.$siteSettingsId;
    }

    /**
     * Cached place summary + reviews for the homepage widget, or null if disabled / error.
     *
     * @return array{
     *   display_name: string,
     *   rating: float|null,
     *   user_rating_count: int|null,
     *   write_review_url: string,
     *   maps_url: string|null,
     *   reviews: array<int, array{
     *     author: string,
     *     rating: float,
     *     text: string,
     *     relative_time: string|null,
     *     photo: string|null,
     *     author_url: string|null,
     *   }>
     * }|null
     */
    public static function forHome(SiteSetting $settings): ?array
    {
        if (! Schema::hasColumn($settings->getTable(), 'google_reviews_enabled')) {
            return null;
        }

        if (! $settings->google_reviews_enabled) {
            return null;
        }

        $placeId = trim((string) $settings->google_place_id);
        $apiKey = $settings->google_places_api_key;
        if ($placeId === '' || ! filled($apiKey)) {
            return null;
        }

        $key = self::cacheKey((int) $settings->id);
        $cached = Cache::get($key);
        if ($cached !== null) {
            return is_array($cached) ? $cached : null;
        }

        $data = self::fetchFromApi($placeId, (string) $apiKey, $settings);
        if ($data !== null) {
            Cache::put($key, $data, self::CACHE_TTL_SECONDS);
        }

        return $data;
    }

    public static function forgetCache(int $siteSettingsId): void
    {
        Cache::forget(self::cacheKey($siteSettingsId));
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function fetchFromApi(string $placeId, string $apiKey, SiteSetting $settings): ?array
    {
        $url = 'https://places.googleapis.com/v1/places/'.rawurlencode($placeId);

        /** @var Response $response */
        $response = Http::timeout(12)
            ->withHeaders([
                'X-Goog-Api-Key' => $apiKey,
                'X-Goog-FieldMask' => 'id,displayName,rating,userRatingCount,reviews,googleMapsUri',
            ])
            ->acceptJson()
            ->get($url);

        if (! $response->successful()) {
            Log::warning('Google Places API request failed', [
                'status' => $response->status(),
                'body' => Str::limit($response->body(), 500),
            ]);

            return null;
        }

        $data = $response->json();
        if (! is_array($data)) {
            return null;
        }

        $displayName = self::textFromLocalized($data['displayName'] ?? null);
        if ($displayName === '') {
            $displayName = (string) ($settings->site_name ?? 'Google reviews');
        }

        $reviewsOut = [];
        $rawReviews = $data['reviews'] ?? [];
        if (is_array($rawReviews)) {
            foreach ($rawReviews as $rev) {
                if (! is_array($rev)) {
                    continue;
                }
                $text = self::textFromLocalized($rev['text'] ?? null);
                if ($text === '') {
                    continue;
                }
                $att = is_array($rev['authorAttribution'] ?? null) ? $rev['authorAttribution'] : [];
                $author = is_string($att['displayName'] ?? null) ? trim($att['displayName']) : '';
                if ($author === '') {
                    $author = 'Reviewer';
                }
                $photo = is_string($att['photoUri'] ?? null) ? trim($att['photoUri']) : null;
                $authorUrl = is_string($att['uri'] ?? null) ? trim($att['uri']) : null;
                $rating = isset($rev['rating']) ? (float) $rev['rating'] : 0.0;
                $when = is_string($rev['relativePublishTimeDescription'] ?? null)
                    ? $rev['relativePublishTimeDescription']
                    : null;

                $reviewsOut[] = [
                    'author' => $author,
                    'rating' => $rating,
                    'text' => $text,
                    'relative_time' => $when,
                    'photo' => $photo !== '' ? $photo : null,
                    'author_url' => $authorUrl !== '' ? $authorUrl : null,
                ];
            }
        }

        $rating = isset($data['rating']) ? (float) $data['rating'] : null;
        $count = isset($data['userRatingCount']) ? (int) $data['userRatingCount'] : null;
        $mapsUrl = is_string($data['googleMapsUri'] ?? null) ? trim($data['googleMapsUri']) : null;
        if ($mapsUrl === '') {
            $mapsUrl = null;
        }

        return [
            'display_name' => $displayName,
            'rating' => $rating,
            'user_rating_count' => $count,
            'write_review_url' => 'https://search.google.com/local/writereview?placeid='.rawurlencode($placeId),
            'maps_url' => $mapsUrl,
            'reviews' => $reviewsOut,
        ];
    }

    /**
     * @param  mixed  $node
     */
    private static function textFromLocalized($node): string
    {
        if (is_string($node)) {
            return trim($node);
        }

        if (! is_array($node)) {
            return '';
        }

        if (isset($node['text']) && is_string($node['text'])) {
            return trim($node['text']);
        }

        return '';
    }
}
