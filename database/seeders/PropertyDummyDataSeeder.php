<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PropertyDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $imgResidential = 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80';
        $imgApartments = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80';
        $imgVillas = 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80';
        $imgCommercial = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80';
        $imgOffice = 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80';
        $imgLand = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80';
        $imgDefaultCategory = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80';

        $residential = PropertyCategory::updateOrCreate(
            ['slug' => 'residential'],
            [
                'parent_id' => null,
                'name' => 'Residential',
                'description' => 'Homes, apartments, and villas for living.',
                'meta_title' => 'Residential properties',
                'meta_description' => 'Browse residential listings in Bangalore.',
                'image_url' => $imgResidential,
                'sort_order' => 10,
                'is_published' => true,
            ]
        );

        PropertyCategory::updateOrCreate(
            ['slug' => 'apartments'],
            [
                'parent_id' => $residential->id,
                'name' => 'Apartments',
                'description' => 'Flats and apartments in gated communities and standalone towers.',
                'image_url' => $imgApartments,
                'sort_order' => 11,
                'is_published' => true,
            ]
        );

        PropertyCategory::updateOrCreate(
            ['slug' => 'villas-row-houses'],
            [
                'parent_id' => $residential->id,
                'name' => 'Villas & row houses',
                'description' => 'Independent floors, villas, and row-house formats.',
                'image_url' => $imgVillas,
                'sort_order' => 12,
                'is_published' => true,
            ]
        );

        $commercial = PropertyCategory::updateOrCreate(
            ['slug' => 'commercial'],
            [
                'parent_id' => null,
                'name' => 'Commercial',
                'description' => 'Office, retail, and mixed-use spaces.',
                'image_url' => $imgCommercial,
                'sort_order' => 20,
                'is_published' => true,
            ]
        );

        PropertyCategory::updateOrCreate(
            ['slug' => 'office-spaces'],
            [
                'parent_id' => $commercial->id,
                'name' => 'Office spaces',
                'description' => 'IT parks, business centres, and standalone offices.',
                'image_url' => $imgOffice,
                'sort_order' => 21,
                'is_published' => true,
            ]
        );

        PropertyCategory::updateOrCreate(
            ['slug' => 'land-plots'],
            [
                'parent_id' => null,
                'name' => 'Land & plots',
                'description' => 'Approved plots and land parcels for development or investment.',
                'image_url' => $imgLand,
                'sort_order' => 30,
                'is_published' => true,
            ]
        );

        PropertyCategory::query()
            ->where(function ($q) {
                $q->whereNull('image_path')->orWhere('image_path', '');
            })
            ->where(function ($q) {
                $q->whereNull('image_url')->orWhere('image_url', '');
            })
            ->update(['image_url' => $imgDefaultCategory]);

        $apartmentsCat = PropertyCategory::where('slug', 'apartments')->first();
        $villasCat = PropertyCategory::where('slug', 'villas-row-houses')->first();
        $landCat = PropertyCategory::where('slug', 'land-plots')->first();
        $officeCat = PropertyCategory::where('slug', 'office-spaces')->first();

        $u = 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1600&q=85';
        $u2 = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=85';
        $u3 = 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=85';
        $uPlan = 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?auto=format&fit=crop&w=1400&q=85';
        $uFloor = 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=85';

        $seedPrefix = 'properties/seed/'.uniqid('demo_', true);
        $showcaseGallery = array_values(array_filter([
            $this->tryStoreImageFromUrl($seedPrefix.'_g1.jpg', $u),
            $this->tryStoreImageFromUrl($seedPrefix.'_g2.jpg', $u2),
            $this->tryStoreImageFromUrl($seedPrefix.'_g3.jpg', $u3),
        ]));
        $showcaseMaster = $this->tryStoreImageFromUrl($seedPrefix.'_master.jpg', $uPlan);
        $showcaseFloors = array_values(array_filter([
            $this->tryStoreImageFromUrl($seedPrefix.'_fp1.jpg', $uFloor),
            $this->tryStoreImageFromUrl($seedPrefix.'_fp2.jpg', $uPlan),
        ]));

        $officeGallery = array_values(array_filter([
            $this->tryStoreImageFromUrl($seedPrefix.'_ofc1.jpg', $imgOffice),
        ]));

        $properties = [
            $this->sampleApartmentShowcase(
                $apartmentsCat?->id,
                $showcaseGallery,
                $showcaseMaster,
                $showcaseFloors,
                $u,
                [$u, $u2, $u3]
            ),
            $this->sampleVilla($villasCat?->id ?? $residential->id),
            $this->samplePlot($landCat?->id),
            $this->sampleOffice($officeCat?->id, $officeGallery),
        ];

        foreach ($properties as $row) {
            $published = (bool) ($row['is_published'] ?? false);
            Property::updateOrCreate(
                ['slug' => $row['slug']],
                array_merge($row, [
                    'published_at' => $published ? $now : null,
                ])
            );
        }
    }

    /**
     * @param  array<int, string>  $galleryPaths
     * @param  array<int, string>  $floorPlanPaths
     * @param  array<int, string>  $galleryHttpsFallbacks
     * @return array<string, mixed>
     */
    private function sampleApartmentShowcase(
        ?int $categoryId,
        array $galleryPaths,
        ?string $masterPlanPath,
        array $floorPlanPaths,
        string $featuredUrl,
        array $galleryHttpsFallbacks
    ): array {
        return [
            'slug' => 'sample-3bhk-whitefield-sale',
            'title' => '3 BHK apartment — Whitefield (sample listing)',
            'property_category_id' => $categoryId,
            'property_type_id' => null,
            'listing_type' => Property::LISTING_SALE,
            'price' => 12500000,
            'price_currency' => 'INR',
            'price_on_request' => false,
            'maintenance_charges' => '₹8,500 / month (incl. clubhouse)',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'balconies' => 2,
            'parking_covered' => 2,
            'built_up_area_sqft' => 1650,
            'carpet_area_sqft' => 1420,
            'plot_area_sqft' => null,
            'floor_number' => 8,
            'total_floors' => 18,
            'facing' => 'East',
            'furnishing' => 'Semi-furnished',
            'age_of_property_years' => 4,
            'possession_status' => 'Ready to move',
            'developer_name' => 'Sample Developers Ltd.',
            'rera_number' => 'PRM/KA/RERA/1251/446/071020',
            'developer_description' => "Listed for UI demos only. Sample Developers is a placeholder name.\n\nTypically you would summarise track record, delivery history, and brand positioning here.",
            'project_land_area' => '3.2 Acres',
            'total_units' => 420,
            'towers_blocks_summary' => '2 towers (A & B), 2B+G+17 floors',
            'unit_variants_summary' => '2, 2.5 & 3 BHK',
            'maps_link_url' => 'https://www.google.com/maps/search/?api=1&query=12.9698,77.7500',
            'price_disclaimer' => "Indicative pricing for demonstration. Final pricing, taxes, and charges are subject to confirmation with the developer / owner.\n\nSample data only — not an offer to sell.",
            'configuration_rows' => [
                ['label' => 'Club membership', 'value' => 'Family (1 year complimentary)'],
                ['label' => 'Power load', 'value' => '5 kW'],
                ['label' => 'Water source', 'value' => 'BWSSB + STP tertiary'],
            ],
            'unit_mix' => [
                ['unit_type' => '2 BHK', 'size_sqft' => '1,180 – 1,240', 'price_label' => 'From ₹ 92 L (sample)'],
                ['unit_type' => '2.5 BHK', 'size_sqft' => '1,380 – 1,450', 'price_label' => 'From ₹ 1.08 Cr (sample)'],
                ['unit_type' => '3 BHK', 'size_sqft' => '1,580 – 1,720', 'price_label' => 'From ₹ 1.25 Cr (sample)'],
            ],
            'specifications' => [
                ['label' => 'Structure', 'value' => 'RCC frame, seismic Zone II compliant (sample)'],
                ['label' => 'Flooring', 'value' => 'Vitrified tiles in living; anti-skid in wet areas'],
                ['label' => 'Windows', 'value' => 'UPVC with mosquito mesh'],
                ['label' => 'Kitchen', 'value' => 'Granite platform, SS sink, provision for water purifier'],
                ['label' => 'Bathrooms', 'value' => 'CP fittings (sample brand), exhaust provision'],
            ],
            'expert_pros' => [
                'Corner unit with dual ventilation in this demo layout.',
                'Proximity to IT corridors and metro (illustrative copy).',
                'Clubhouse, pool, and indoor games — good for families.',
            ],
            'expert_cons' => [
                'Peak-hour traffic on main approach roads (typical for the micro-market).',
                'Verify OC / CC and loan tie-ups independently.',
            ],
            'project_faqs' => [
                [
                    'question' => 'Is this a real listing?',
                    'answer' => 'No. This page uses sample text and numbers to preview the property detail layout.',
                ],
                [
                    'question' => 'What should replace this content?',
                    'answer' => 'Accurate pricing, legal status, possession timeline, and developer disclosures as per your compliance process.',
                ],
            ],
            'address_line1' => 'Sample Greens Phase 2, Tower B',
            'address_line2' => 'Near Hope Farm Junction',
            'locality' => 'Whitefield',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'postal_code' => '560066',
            'country' => 'India',
            'latitude' => 12.9698,
            'longitude' => 77.7500,
            'summary' => "Spacious 3 BHK with a wide balcony, modular kitchen provisions, and a split air-conditioner in the living area.\n\nThis overview appears in the “Overview” block on the public detail page — use it for a short, scannable pitch.",
            'description' => <<<'HTML'
<p>This <strong>rich HTML body</strong> appears under <em>More about this project</em>. Use headings, lists, and links as needed.</p>
<ul>
  <li>Bullet one — highlight a buyer benefit.</li>
  <li>Bullet two — connectivity or infrastructure.</li>
  <li>Bullet three — lifestyle / amenities story.</li>
</ul>
<p>Replace every paragraph with verified facts before publishing real inventory.</p>
HTML,
            'amenities' => [
                'Clubhouse', 'Swimming pool', 'Gym', 'Indoor games', 'Children\'s play area',
                'Power backup (common areas + partial in flats)', 'Lift', '24×7 security', 'Visitor parking',
            ],
            'meta_title' => '3 BHK Whitefield — sample property detail | PropUpdate',
            'meta_description' => 'Demo listing: 3 BHK apartment in Whitefield with full fields for testing the property microsite UI.',
            'meta_keywords' => 'sample, Whitefield, 3 BHK, Bangalore, demo',
            'master_plan_path' => $masterPlanPath,
            'floor_plan_paths' => $floorPlanPaths !== [] ? $floorPlanPaths : null,
            'gallery_paths' => $this->mergeSampleGallery($galleryPaths, $galleryHttpsFallbacks),
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 1,
            'featured_image_url' => $featuredUrl,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function sampleVilla(int $categoryId): array
    {
        return [
            'slug' => 'sample-villa-sarjapur-sale',
            'title' => '4 BHK villa — Sarjapur Road (sample)',
            'property_category_id' => $categoryId,
            'property_type_id' => null,
            'listing_type' => Property::LISTING_SALE,
            'price' => 28500000,
            'price_currency' => 'INR',
            'price_on_request' => false,
            'maintenance_charges' => '₹6 / sq ft (society maintenance — sample)',
            'bedrooms' => 4,
            'bathrooms' => 4,
            'balconies' => 3,
            'parking_covered' => 3,
            'built_up_area_sqft' => 3200,
            'carpet_area_sqft' => 2750,
            'plot_area_sqft' => 2400,
            'floor_number' => null,
            'total_floors' => 3,
            'facing' => 'North-East',
            'furnishing' => 'Unfurnished',
            'age_of_property_years' => 0,
            'possession_status' => 'Under construction — Dec 2026',
            'developer_name' => 'Sample Developers Ltd.',
            'rera_number' => 'PRM/KA/RERA/1251/446/071021',
            'developer_description' => "Gated villa community (demo copy).\n\nOutline phases, handover track record, and after-sales here.",
            'project_land_area' => '12 Acres (sample)',
            'total_units' => 180,
            'towers_blocks_summary' => 'Low-rise clusters — 8 units per acre (illustrative)',
            'unit_variants_summary' => '4 & 5 BHK villas',
            'maps_link_url' => 'https://www.google.com/maps/search/?api=1&query=Sarjapur+Road+Bengaluru',
            'price_disclaimer' => 'Villa pricing varies by plot size and facing. Sample numbers for UI only.',
            'configuration_rows' => [
                ['label' => 'Private garden', 'value' => 'Approx. 420 sq ft'],
                ['label' => 'Terrace rights', 'value' => 'Exclusive to unit'],
            ],
            'unit_mix' => [
                ['unit_type' => '4 BHK villa', 'size_sqft' => '3,000 – 3,400', 'price_label' => 'From ₹ 2.65 Cr (sample)'],
                ['unit_type' => '5 BHK villa', 'size_sqft' => '3,800 – 4,200', 'price_label' => 'On request'],
            ],
            'specifications' => [
                ['label' => 'Wall finish', 'value' => 'External texture + weather coat (sample)'],
                ['label' => 'Security', 'value' => 'Perimeter CCTV, boom barrier'],
            ],
            'expert_pros' => ['Plot-linked villa format — good for end-use buyers who want space.'],
            'expert_cons' => ['Construction risk until possession — monitor RERA filings.'],
            'project_faqs' => [
                ['question' => 'Is the handover date fixed?', 'answer' => 'This is placeholder text. Replace with actual construction-linked timelines.'],
            ],
            'address_line1' => 'Sample County Villas, Plot 14',
            'address_line2' => 'Sarjapur–Attibele Road',
            'locality' => 'Sarjapur Road',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'postal_code' => '562125',
            'country' => 'India',
            'latitude' => 12.8330,
            'longitude' => 77.6773,
            'summary' => "Gated community villa with private garden, triple-height living in demo layout, and provision for home lift.\n\nUse the summary for a concise hook above the fold.",
            'description' => '<p>Villa body copy: describe layout, basement / terrace usage, and Vastu notes if relevant. <strong>All sample content.</strong></p>',
            'amenities' => ['Private garden', 'Covered parking', 'Clubhouse access', 'Security', 'Rainwater harvesting'],
            'meta_title' => '4 BHK villa Sarjapur — sample | PropUpdate',
            'meta_description' => 'Demo villa listing with project sections filled for property detail UI testing.',
            'meta_keywords' => 'villa, Sarjapur, sample, demo',
            'master_plan_path' => null,
            'floor_plan_paths' => null,
            'gallery_paths' => $this->mergeSampleGallery([], [
                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1600585154084-4e5fe7c39198?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=80',
            ]),
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 2,
            'featured_image_url' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function samplePlot(?int $categoryId): array
    {
        return [
            'slug' => 'sample-plot-devanahalli',
            'title' => 'Approved residential plot — Devanahalli (sample)',
            'property_category_id' => $categoryId,
            'property_type_id' => null,
            'listing_type' => Property::LISTING_SALE,
            'price' => 6800000,
            'price_currency' => 'INR',
            'price_on_request' => false,
            'maintenance_charges' => null,
            'bedrooms' => null,
            'bathrooms' => null,
            'balconies' => null,
            'parking_covered' => null,
            'built_up_area_sqft' => null,
            'carpet_area_sqft' => null,
            'plot_area_sqft' => 2400,
            'floor_number' => null,
            'total_floors' => null,
            'facing' => 'East',
            'furnishing' => null,
            'age_of_property_years' => null,
            'possession_status' => 'NA — vacant plot',
            'developer_name' => 'Sample Layouts Pvt. Ltd.',
            'rera_number' => 'Layout approval ref. SAMPLE/LAO/2024/001',
            'developer_description' => "BMRDA / local authority approvals must be verified independently.\n\nThis block shows how developer notes render for land listings.",
            'project_land_area' => '22 Acres layout (sample)',
            'total_units' => 140,
            'towers_blocks_summary' => 'Plots only — 30′ & 40′ roads (illustrative)',
            'unit_variants_summary' => '30×40, 30×50, 40×60 sites',
            'maps_link_url' => 'https://www.google.com/maps/search/?api=1&query=Devanahalli+Bengaluru',
            'price_disclaimer' => 'Plot prices are indicative. Registration, corner premium, and development charges may apply.',
            'configuration_rows' => [
                ['label' => 'Road width (front)', 'value' => '40 ft (sample)'],
                ['label' => 'Corner plot', 'value' => 'Yes — demo flag'],
            ],
            'unit_mix' => [
                ['unit_type' => '30×40', 'size_sqft' => '1,200', 'price_label' => '₹ 68 L (sample)'],
                ['unit_type' => '30×50', 'size_sqft' => '1,500', 'price_label' => 'On request'],
            ],
            'specifications' => [
                ['label' => 'Title', 'value' => 'Illustrative — verify encumbrance certificate'],
                ['label' => 'Utilities', 'value' => 'Underground drainage & UGD (sample)'],
            ],
            'expert_pros' => ['Airport proximity narrative (demo) for Devanahalli micro-market.'],
            'expert_cons' => ['Infrastructure maturity varies by pocket — site visit essential.'],
            'project_faqs' => [
                ['question' => 'Is the layout approved?', 'answer' => 'Replace with actual authority references and document links.'],
            ],
            'address_line1' => 'Sample Serene Layout, Site 88',
            'address_line2' => 'Near Nandi Hills Road',
            'locality' => 'Devanahalli',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'postal_code' => '562110',
            'country' => 'India',
            'latitude' => 13.2475,
            'longitude' => 77.7063,
            'summary' => "East-facing corner site with 40 ft road frontage in this demo record.\n\nPlot listings still show Overview, Configuration, Maps, and FAQs sections when filled.",
            'description' => '<p>Plot <strong>description</strong>: explain dimensions, setbacks, and any development charges. Sample content only.</p>',
            'amenities' => ['Underground electricity', 'Storm water drain', 'Tree-lined avenue', 'Park (proposed — sample)'],
            'meta_title' => 'Residential plot Devanahalli — sample | PropUpdate',
            'meta_description' => 'Demo plot listing to exercise land-style fields on the property page.',
            'meta_keywords' => 'plot, Devanahalli, land, sample',
            'master_plan_path' => null,
            'floor_plan_paths' => null,
            'gallery_paths' => $this->mergeSampleGallery([], [
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1628624747186-bd7f0f58dae0?auto=format&fit=crop&w=1200&q=80',
            ]),
            'is_published' => true,
            'is_featured' => false,
            'sort_order' => 3,
            'featured_image_url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
        ];
    }

    /**
     * @param  array<int, string|null>  $diskPaths
     * @param  array<int, string>  $httpsFallbacks
     * @return array<int, string>|null
     */
    private function mergeSampleGallery(array $diskPaths, array $httpsFallbacks): ?array
    {
        $out = [];
        foreach ($diskPaths as $p) {
            if (is_string($p) && $p !== '') {
                $out[] = $p;
            }
        }
        foreach ($httpsFallbacks as $u) {
            if ($u !== '' && ! in_array($u, $out, true)) {
                $out[] = $u;
            }
        }

        return $out === [] ? null : array_values($out);
    }

    /**
     * @param  array<int, string>  $galleryPaths
     * @return array<string, mixed>
     */
    private function sampleOffice(?int $categoryId, array $galleryPaths): array
    {
        return [
            'slug' => 'sample-office-koramangala-rent',
            'title' => 'Office space for lease — Koramangala (sample)',
            'property_category_id' => $categoryId,
            'property_type_id' => null,
            'listing_type' => Property::LISTING_RENT,
            'price' => 185000,
            'price_currency' => 'INR',
            'price_on_request' => false,
            'maintenance_charges' => '₹22 / sq ft including CAM (sample)',
            'bedrooms' => null,
            'bathrooms' => 2,
            'balconies' => 0,
            'parking_covered' => 3,
            'built_up_area_sqft' => 2200,
            'carpet_area_sqft' => 1980,
            'plot_area_sqft' => null,
            'floor_number' => 5,
            'total_floors' => 12,
            'facing' => 'South',
            'furnishing' => 'Warm shell',
            'age_of_property_years' => 6,
            'possession_status' => 'Immediate',
            'developer_name' => 'Sample Business Parks',
            'rera_number' => null,
            'developer_description' => "Grade-A office block (demo).\n\nDescribe landlord model, fit-out period, and escalation clauses here.",
            'project_land_area' => '1.8 Acres (sample campus)',
            'total_units' => 1,
            'towers_blocks_summary' => 'Single tower — 2B+G+12',
            'unit_variants_summary' => '2,200 sq ft plate (sample)',
            'maps_link_url' => 'https://www.google.com/maps/search/?api=1&query=12.9352,77.6245',
            'price_disclaimer' => 'Rent is per month before GST. Lease term and deposit per agreement — illustrative.',
            'configuration_rows' => [
                ['label' => 'Ceiling height', 'value' => 'Clear 2.85 m (sample)'],
                ['label' => 'Floor loading', 'value' => '4 kN/m²'],
                ['label' => 'WC provision', 'value' => '2 executive washrooms'],
            ],
            'unit_mix' => [
                ['unit_type' => 'Suite A', 'size_sqft' => '2,200', 'price_label' => '₹ 1.85 L / month'],
                ['unit_type' => 'Suite B', 'size_sqft' => '1,650', 'price_label' => 'Leased (sample)'],
            ],
            'specifications' => [
                ['label' => 'HVAC', 'value' => 'VRV-ready risers (sample)'],
                ['label' => 'Power', 'value' => '100 kW backup (illustrative)'],
                ['label' => 'Fiber', 'value' => 'Dual ISP entry'],
            ],
            'expert_pros' => ['Koramangala micro-market — strong F&B and talent pool access (demo copy).'],
            'expert_cons' => ['Parking ratios — confirm visitor policy with facility management.'],
            'project_faqs' => [
                ['question' => 'Is fit-out included?', 'answer' => 'This listing shows warm shell. Replace with actual landlord capex / tenant scope.'],
                ['question' => 'Minimum lease term?', 'answer' => 'Typically 3+3 or 5 years — sample answer only.'],
            ],
            'address_line1' => '5th Floor, Sample Tech Hub',
            'address_line2' => '80 Feet Road, 7th Block',
            'locality' => 'Koramangala',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'postal_code' => '560095',
            'country' => 'India',
            'latitude' => 12.9352,
            'longitude' => 77.6245,
            'summary' => "Warm-shell office with an efficient floor plate, good daylight on two sides, and dedicated washrooms.\n\nIdeal template for commercial lease listings in the detail UI.",
            'description' => <<<'HTML'
<p>Office <strong>body</strong>: describe access control, lift banks, cafeteria, and meeting-room commons.</p>
<p>Add a table or embedded map link if your CMS allows. <em>Sample HTML only.</em></p>
HTML,
            'amenities' => [
                '24×7 security', 'High-speed lifts', 'DG backup', 'Central AC plant', 'Visitor lobby',
                'Bike parking', 'Rainwater harvesting',
            ],
            'meta_title' => 'Office for rent Koramangala — sample | PropUpdate',
            'meta_description' => 'Demo commercial listing with full specs for property detail page QA.',
            'meta_keywords' => 'office, Koramangala, rent, sample',
            'master_plan_path' => null,
            'floor_plan_paths' => null,
            'gallery_paths' => $this->mergeSampleGallery($galleryPaths, [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80',
            ]),
            'is_published' => true,
            'is_featured' => false,
            'sort_order' => 4,
            'featured_image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
        ];
    }

    private function tryStoreImageFromUrl(string $relativePath, string $url): ?string
    {
        try {
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            Storage::disk('public')->put($relativePath, $response->body());

            return $relativePath;
        } catch (\Throwable) {
            return null;
        }
    }
}
