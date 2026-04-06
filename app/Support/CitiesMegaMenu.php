<?php

namespace App\Support;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

final class CitiesMegaMenu
{
    private const CARD_LIMIT = 5;

    /** @internal Serialized mega JSON key for “all cities” */
    public const ALL_KEY = '__all__';

    /**
     * Distinct published cities and up to CARD_LIMIT cards per city (plus “all” bucket).
     *
     * @return array{cities: array<int, string>, cardsByCity: array<string, array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>>}
     */
    public static function data(): array
    {
        if (! Schema::hasTable('properties')) {
            return ['cities' => [], 'cardsByCity' => []];
        }

        $cities = Property::query()
            ->published()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->values()
            ->all();

        $cardsByCity = [
            self::ALL_KEY => self::cardsForQuery(Property::query()->published()),
        ];

        foreach ($cities as $city) {
            $cardsByCity[$city] = self::cardsForQuery(
                Property::query()->published()->where('city', $city)
            );
        }

        return [
            'cities' => $cities,
            'cardsByCity' => $cardsByCity,
        ];
    }

    /**
     * @param  Builder<Property>  $base
     * @return array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>
     */
    private static function cardsForQuery(Builder $base): array
    {
        $properties = (clone $base)
            ->orderByDesc('is_featured')
            ->orderByDesc('sort_order')
            ->orderByDesc('updated_at')
            ->limit(self::CARD_LIMIT)
            ->get();

        $out = [];
        foreach ($properties as $property) {
            $loc = collect([$property->locality, $property->city])->filter()->implode(', ');

            $out[] = [
                'url' => route('properties.show', $property),
                'title' => Str::limit($property->title, 54),
                'image' => $property->featuredBannerUrl(),
                'location' => $loc !== '' ? $loc : '—',
                'badge' => self::badgeLabel($property),
            ];
        }

        return $out;
    }

    private static function badgeLabel(Property $property): string
    {
        if ($property->is_featured) {
            return 'Featured';
        }
        if ($property->is_new_launch) {
            return 'New launch';
        }

        $labels = Property::listingTypeOptions();

        return $labels[$property->listing_type] ?? 'Listing';
    }
}
