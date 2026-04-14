<?php

namespace App\Support;

use App\Models\Award;
use Illuminate\Support\Facades\Schema;

final class FooterAwards
{
    private const LIMIT = 10;

    /**
     * @return array<int, array{image_url: string, href: string, label: string}>
     */
    public static function latestItems(): array
    {
        $rows = [];
        if (! Schema::hasTable('awards')) {
            return $rows;
        }

        Award::query()
            ->where('is_published', true)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->limit(40)
            ->get()
            ->each(function (Award $award) use (&$rows): void {
                if (count($rows) >= self::LIMIT) {
                    return;
                }
                $img = $award->imagePublicUrl();
                if (! $img) {
                    return;
                }
                $href = trim((string) ($award->link_url ?? ''));
                if ($href === '' || ! preg_match('/\Ahttps?:\/\//i', $href)) {
                    $href = route('home');
                }
                $rows[] = [
                    'image_url' => $img,
                    'href' => $href,
                    'label' => $award->title ?: 'Award',
                ];
            });

        return $rows;
    }
}

