<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GalleryDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        if (GalleryItem::query()->exists()) {
            return;
        }

        $rows = [
            [
                'title' => 'Modern high-rise living',
                'caption' => 'Glass towers and skyline views — premium apartment corridors.',
                'image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 10,
            ],
            [
                'title' => 'Poolside villa',
                'caption' => 'Open terraces, water features, and relaxed luxury.',
                'image_url' => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 20,
            ],
            [
                'title' => 'Commercial skyline',
                'caption' => 'Grade-A workspace and retail in the CBD.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 30,
            ],
            [
                'title' => 'Open plots & land',
                'caption' => 'Clear titles and growth corridors for investors.',
                'image_url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 40,
            ],
            [
                'title' => 'Family home facade',
                'caption' => 'Warm lighting and welcoming entryways.',
                'image_url' => 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 50,
            ],
            [
                'title' => 'Waterfront residences',
                'caption' => 'Lake or sea-facing inventory with strong rental demand.',
                'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 60,
            ],
            [
                'title' => 'Interior living space',
                'caption' => 'Thoughtful layouts and natural light.',
                'image_url' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 70,
            ],
            [
                'title' => 'Evening architecture',
                'caption' => 'Projects that stand out after sunset.',
                'image_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 80,
            ],
            [
                'title' => 'Green community',
                'caption' => 'Parks, walkways, and low-density clusters.',
                'image_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 90,
            ],
        ];

        foreach ($rows as $row) {
            GalleryItem::create([
                'title' => $row['title'],
                'caption' => $row['caption'],
                'image_path' => null,
                'image_url' => $row['image_url'],
                'sort_order' => $row['sort_order'],
                'is_published' => true,
            ]);
        }
    }
}
