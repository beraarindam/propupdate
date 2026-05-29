<?php

namespace App\Support;

use App\Models\Property;
use App\Models\PropertyArea;
use Illuminate\Support\Facades\Schema;

final class CitiesMegaMenu
{
    private const AREA_LIMIT = 5;

    /**
     * Up to AREA_LIMIT published areas as mega-menu cards (same shape as property cards).
     *
     * @return array{areaCards: array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>}
     */
    public static function data(): array
    {
        if (! Schema::hasTable('property_areas')) {
            return ['areaCards' => []];
        }

        $areas = PropertyArea::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(self::AREA_LIMIT)
            ->get();

        $areaCards = [];
        foreach ($areas as $area) {
            $count = Schema::hasTable('properties')
                ? $area->properties()->published()->count()
                : 0;

            $image = $area->bannerImageUrl();
            if (! $image && Schema::hasTable('properties')) {
                $first = $area->properties()
                    ->published()
                    ->orderByDesc('is_featured')
                    ->orderByDesc('sort_order')
                    ->first();
                $image = $first instanceof Property ? $first->featuredBannerUrl() : null;
            }

            $areaCards[] = [
                'url' => route('properties.index', ['area_id' => $area->id]),
                'title' => (string) $area->name,
                'image' => $image,
                'location' => $count === 1 ? '1 property' : $count.' properties',
                'badge' => 'Area',
            ];
        }

        return ['areaCards' => $areaCards];
    }
}
