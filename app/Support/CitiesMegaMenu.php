<?php

namespace App\Support;

use App\Models\Property;
use App\Models\PropertyArea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

final class CitiesMegaMenu
{
    private const CARD_LIMIT = 5;

    /** @internal Serialized mega JSON key for “all areas” */
    public const ALL_KEY = '__all__';

    /**
     * Distinct published areas and up to CARD_LIMIT cards per area (plus “all” bucket).
     *
     * @return array{areas: array<int, array{id: int, name: string}>, cardsByCity: array<string, array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>>}
     */
    public static function data(): array
    {
        if (! Schema::hasTable('properties') || ! Schema::hasTable('property_areas')) {
            return ['areas' => [], 'cardsByCity' => []];
        }

        $areas = PropertyArea::query()
            ->where('is_published', true)
            ->whereHas('properties', fn (Builder $q) => $q->published())
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (PropertyArea $area) => ['id' => (int) $area->id, 'name' => (string) $area->name])
            ->values()
            ->all();

        $cardsByCity = [
            self::ALL_KEY => self::cardsForQuery(Property::query()->published()),
        ];

        foreach ($areas as $area) {
            $cardsByCity[(string) $area['id']] = self::cardsForQuery(
                Property::query()->published()->where('property_area_id', $area['id'])
            );
        }

        return [
            'areas' => $areas,
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
            ->with('area')
            ->orderByDesc('is_featured')
            ->orderByDesc('sort_order')
            ->orderByDesc('updated_at')
            ->limit(self::CARD_LIMIT)
            ->get();

        $out = [];
        foreach ($properties as $property) {
            $areaName = $property->area?->name;
            $loc = collect([$areaName, $property->locality, $property->city])->filter()->implode(', ');

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
