<?php

namespace App\Support;

use App\Models\Property;
use Illuminate\Support\Facades\Schema;

final class NewLaunchesMegaMenu
{
    private const LIMIT = 5;

    /**
     * Cards for the desktop “New Launches” mega menu — **property listings only**
     * (`is_new_launch`). No CMS projects and no generic listings.
     *
     * @return array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>
     */
    public static function cards(): array
    {
        if (! Schema::hasTable('properties')) {
            return [];
        }

        $launches = Property::query()
            ->published()
            ->newLaunch()
            ->orderByDesc('is_featured')
            ->orderByDesc('sort_order')
            ->orderByDesc('updated_at')
            ->limit(self::LIMIT)
            ->get();

        $out = [];
        foreach ($launches as $property) {
            $loc = collect([$property->locality, $property->city])->filter()->implode(', ');

            $out[] = [
                'url' => route('properties.show', $property),
                'title' => $property->title,
                'image' => $property->featuredBannerUrl(),
                'location' => $loc !== '' ? $loc : 'Bangalore',
                'badge' => $property->is_featured ? 'Featured' : 'New launch',
            ];
        }

        return $out;
    }
}
