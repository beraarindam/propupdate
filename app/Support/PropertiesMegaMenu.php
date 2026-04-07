<?php

namespace App\Support;

use App\Models\PropertyCategory;
use Illuminate\Support\Facades\Schema;

final class PropertiesMegaMenu
{
    private const LIMIT = 4;

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
        foreach ($categories as $category) {
            $out[] = [
                'url' => route('properties.index', ['category_id' => $category->id]),
                'title' => $category->name,
                'image' => $category->bannerImageUrl(),
                'location' => 'Browse category',
                'badge' => 'Category',
            ];
        }

        return $out;
    }
}
