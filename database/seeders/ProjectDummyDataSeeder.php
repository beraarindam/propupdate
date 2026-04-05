<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'slug' => 'prestige-lakeside-habitat-sample',
                'title' => 'Prestige Lakeside Habitat (sample)',
                'summary' => 'Lake-facing towers in Varthur — sample project page for demos and SEO testing.',
                'body' => '<p>This is <strong>sample project body</strong> content. Replace with real copy, floor plans, and RERA details.</p><ul><li>Phase-wise possession</li><li>Clubhouse & sports courts</li><li>Strong north Bangalore connectivity</li></ul>',
                'location' => 'Varthur, East Bangalore',
                'developer_name' => 'Prestige Group',
                'meta_title' => 'Prestige Lakeside Habitat | Sample project | PropUpdate',
                'meta_description' => 'Demo new-launch project page with SEO fields — location, developer, and long-form story.',
                'meta_keywords' => 'bangalore, new launch, sample project, varthur',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 10,
            ],
            [
                'slug' => 'greenfield-gardens-sample',
                'title' => 'Greenfield Gardens (sample)',
                'summary' => 'Low-density plotted development — second sample for listing grids.',
                'body' => '<p>Another <em>placeholder</em> project for layout tests. Add pricing bands, master plan links, and FAQs in the editor.</p>',
                'location' => 'North Bangalore',
                'developer_name' => 'Greenfield Homes',
                'meta_title' => 'Greenfield Gardens plotted development | PropUpdate sample',
                'meta_description' => 'Sample plotted community page with meta description for search results.',
                'meta_keywords' => 'plots, north bangalore, sample',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 20,
            ],
            [
                'slug' => 'sobha-neopolis-panathur',
                'title' => 'Sobha Neopolis',
                'summary' => 'Large-format apartments with resort-style amenities and strong ORR access.',
                'body' => '<p>Sample <strong>new launch</strong> narrative for Panathur–Bellandur corridor buyers. Update with official pricing, RERA number, and possession timeline.</p><p>Typical highlights: multiple towers, landscaped podiums, and clubhouse facilities.</p>',
                'location' => 'Panathur, East Bangalore',
                'developer_name' => 'Sobha Limited',
                'meta_title' => 'Sobha Neopolis Panathur | New launch | PropUpdate',
                'meta_description' => 'Explore Sobha Neopolis — sample project listing for PropUpdate demos.',
                'meta_keywords' => 'Sobha, Panathur, ORR, new launch Bangalore',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 30,
            ],
            [
                'slug' => 'brigade-horizon-mysore-road',
                'title' => 'Brigade Horizon',
                'summary' => 'Metro-linked corridor project — sample for south/west Bangalore audiences.',
                'body' => '<p>Placeholder content for a <em>metro-adjacent</em> launch story. Swap in actual inventory mix, payment plan, and developer disclosures.</p>',
                'location' => 'Mysore Road corridor',
                'developer_name' => 'Brigade Group',
                'meta_title' => 'Brigade Horizon Mysore Road | PropUpdate sample',
                'meta_description' => 'Sample Brigade Horizon project card and detail page content.',
                'meta_keywords' => 'Brigade, Mysore Road, Bangalore project',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 40,
            ],
            [
                'slug' => 'godrej-ananda-bagalur',
                'title' => 'Godrej Ananda',
                'summary' => 'Aerospace / Bagalur micro-market — entry-focused sample listing.',
                'body' => '<p>Demo copy for <strong>North Bangalore</strong> growth story. Replace with verified specs, approvals, and contact workflow.</p><ul><li>Compact efficient layouts</li><li>Workforce catchment near KIAL corridor</li></ul>',
                'location' => 'Bagalur, North Bangalore',
                'developer_name' => 'Godrej Properties',
                'meta_title' => 'Godrej Ananda Bagalur | PropUpdate sample',
                'meta_description' => 'Sample Godrej Ananda project for PropUpdate project grid and SEO.',
                'meta_keywords' => 'Godrej, Bagalur, North Bangalore launch',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1600596542815-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 50,
            ],
            [
                'slug' => 'purva-meridian-begur',
                'title' => 'Purva Meridian',
                'summary' => 'Begur Road belt — mid-segment sample with strong connectivity story.',
                'body' => '<p>Illustrative <strong>Begur/Electronic City</strong> pitch. Admin can paste full microsite HTML from the project editor.</p>',
                'location' => 'Begur, South Bangalore',
                'developer_name' => 'Puravankara',
                'meta_title' => 'Purva Meridian Begur | PropUpdate sample',
                'meta_description' => 'Sample Purva Meridian listing for PropUpdate projects index.',
                'meta_keywords' => 'Purva, Begur, South Bangalore',
                'featured_image_path' => null,
                'featured_image_url' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 15,
            ],
        ];

        foreach ($rows as $row) {
            Project::updateOrCreate(
                ['slug' => $row['slug']],
                array_merge($row, [
                    'published_at' => $now,
                ])
            );
        }
    }
}
