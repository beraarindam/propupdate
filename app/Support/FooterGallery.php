<?php

namespace App\Support;

use App\Models\GalleryItem;
use App\Models\Project;
use App\Models\Property;
use Illuminate\Support\Facades\Schema;

final class FooterGallery
{
    private const LIMIT = 6;

    /**
     * Latest images for the footer grid: published CMS gallery first, then listings.
     *
     * @return array<int, array{image_url: string, href: string, label: string}>
     */
    public static function latestItems(): array
    {
        $seen = [];
        $rows = [];

        $add = function (?string $img, string $href, string $label) use (&$seen, &$rows): void {
            if ($img === null || $img === '') {
                return;
            }
            if (isset($seen[$img])) {
                return;
            }
            if (count($rows) >= self::LIMIT) {
                return;
            }
            $seen[$img] = true;
            $rows[] = [
                'image_url' => $img,
                'href' => $href,
                'label' => $label,
            ];
        };

        if (Schema::hasTable('gallery_items')) {
            GalleryItem::query()
                ->where('is_published', true)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->limit(24)
                ->get()
                ->each(function (GalleryItem $g) use ($add) {
                    $add($g->imagePublicUrl(), route('gallery.index'), $g->title ?: 'Gallery');
                });
        }

        if (count($rows) < self::LIMIT && Schema::hasTable('properties')) {
            Property::query()
                ->where('is_published', true)
                ->whereNotNull('published_at')
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->limit(30)
                ->get()
                ->each(function (Property $p) use ($add) {
                    $add($p->featuredBannerUrl(), route('properties.show', $p), $p->title);
                });
        }

        if (count($rows) < self::LIMIT && Schema::hasTable('projects')) {
            Project::query()
                ->where('is_published', true)
                ->whereNotNull('published_at')
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->limit(30)
                ->get()
                ->each(function (Project $pr) use ($add) {
                    $add($pr->featuredBannerUrl(), route('projects.show', $pr), $pr->title);
                });
        }

        if ($rows === []) {
            return self::placeholders();
        }

        return $rows;
    }

    /**
     * @return array<int, array{image_url: string, href: string, label: string}>
     */
    private static function placeholders(): array
    {
        $href = route('gallery.index');
        $urls = [
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=400&q=80',
            'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=400&q=80',
        ];
        $out = [];
        foreach ($urls as $i => $url) {
            $out[] = [
                'image_url' => $url,
                'href' => $href,
                'label' => 'Gallery — image '.($i + 1),
            ];
        }

        return $out;
    }
}
