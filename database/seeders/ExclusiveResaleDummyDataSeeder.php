<?php

namespace Database\Seeders;

use App\Models\ExclusiveResaleListing;
use Illuminate\Database\Seeder;

class ExclusiveResaleDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'property_code' => 'PU-01',
                'title' => 'Purva Atmosphere',
                'status_badge' => 'Ready To Move',
                'location' => 'Thanisandra Road, Bangalore North',
                'property_type' => 'Apartment',
                'configuration' => '4 BHK',
                'area_display' => '2400 Sqft',
                'market_price' => '4.1 Cr',
                'asking_price' => '3.95 Cr',
                'rate_per_sqft' => '16458',
                'image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 10,
            ],
            [
                'property_code' => 'PU-02',
                'title' => 'Brigade Lakefront',
                'status_badge' => 'Ready To Move',
                'location' => 'Whitefield, Bangalore East',
                'property_type' => 'Apartment',
                'configuration' => '2 BHK',
                'area_display' => '1280 Sqft',
                'market_price' => '1.25 Cr',
                'asking_price' => '1.18 Cr',
                'rate_per_sqft' => '9218',
                'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 20,
            ],
            [
                'property_code' => 'PU-03',
                'title' => 'Sobha Dream Acres',
                'status_badge' => 'Ready To Move',
                'location' => 'Panathur, Bangalore',
                'property_type' => 'Apartment',
                'configuration' => '3 BHK',
                'area_display' => '1375 Sqft',
                'market_price' => '1.85 Cr',
                'asking_price' => '1.72 Cr',
                'rate_per_sqft' => '12509',
                'image_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 30,
            ],
        ];

        foreach ($rows as $row) {
            ExclusiveResaleListing::updateOrCreate(
                ['property_code' => $row['property_code']],
                array_merge($row, [
                    'image_path' => null,
                    'is_published' => true,
                    'published_at' => $now,
                ])
            );
        }
    }
}
