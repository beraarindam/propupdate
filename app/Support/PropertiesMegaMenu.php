<?php

namespace App\Support;

use App\Models\Property;
use Illuminate\Support\Facades\Schema;

final class PropertiesMegaMenu
{
    private const LIMIT = 5;

    /**
     * Latest published property listings for the “Properties” mega menu.
     *
     * @return array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>
     */
    public static function cards(): array
    {
        if (! Schema::hasTable('properties')) {
            return [];
        }

        $properties = Property::query()
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('sort_order')
            ->orderByDesc('updated_at')
            ->limit(self::LIMIT)
            ->get();

        $out = [];
        foreach ($properties as $property) {
            $loc = collect([$property->locality, $property->city])->filter()->implode(', ');

            $out[] = [
                'url' => route('properties.show', $property),
                'title' => $property->title,
                'image' => $property->featuredBannerUrl(),
                'location' => $loc !== '' ? $loc : 'Bangalore',
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
