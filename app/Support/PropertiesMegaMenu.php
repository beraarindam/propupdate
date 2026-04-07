<?php

namespace App\Support;

use App\Models\PropertyCategory;
use Illuminate\Support\Facades\Schema;

final class PropertiesMegaMenu
{
    private const LIMIT = 4;
    private const FALLBACK_IMAGES = [
        'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80',
    ];

    /**
     * Top property categories for the “Properties” mega menu (same pattern as homepage).
     *
     * @return array<int, array{url: string, title: string, image: ?string, location: string, badge: string}>
     */
    public static function cards(): array
    {
        if (! Schema::hasTable('property_categories')) {
            return [];
        }

        $categories = PropertyCategory::query()
            ->where('is_published', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(self::LIMIT)
            ->get();

        if ($categories->isEmpty()) {
            $categories = PropertyCategory::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(self::LIMIT)
                ->get();
        }

        $out = [];
        foreach ($categories as $idx => $category) {
            $out[] = [
                'url' => route('properties.index', ['category_id' => $category->id]),
                'title' => $category->name,
                'image' => self::resolveCardImage($category, $idx),
                'location' => 'Browse category',
                'badge' => 'Category',
            ];
        }

        return $out;
    }

    private static function resolveCardImage(PropertyCategory $category, int $idx): ?string
    {
        $img = $category->bannerImageUrl();
        if ($img) {
            return $img;
        }

        $child = PropertyCategory::query()
            ->where('parent_id', $category->id)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->first(function (PropertyCategory $c) {
                return $c->bannerImageUrl() !== null;
            });
        if ($child) {
            return $child->bannerImageUrl();
        }

        return self::FALLBACK_IMAGES[$idx] ?? self::FALLBACK_IMAGES[0];
    }
}
