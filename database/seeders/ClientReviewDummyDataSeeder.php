<?php

namespace Database\Seeders;

use App\Models\ClientReview;
use Illuminate\Database\Seeder;

class ClientReviewDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'reviewer_name' => 'Priya Sharma',
                'content' => 'PropUpdate helped us shortlist new launches in Whitefield within a week. Clear comparisons, honest guidance on pricing, and zero pressure. We booked our 3 BHK after two site visits they arranged.',
                'rating' => 5,
                'image_url' => 'https://i.pravatar.cc/150?img=5',
                'sort_order' => 10,
            ],
            [
                'reviewer_name' => 'Rahul Menon',
                'content' => 'I was looking for an office space in Koramangala on lease. The team filtered options by budget and fit-out timeline. Paperwork felt straightforward and they stayed responsive on WhatsApp throughout.',
                'rating' => 5,
                'image_url' => 'https://i.pravatar.cc/150?img=12',
                'sort_order' => 20,
            ],
            [
                'reviewer_name' => 'Ananya Reddy',
                'content' => 'We sold our resale flat in North Bangalore through their exclusive resale desk. They verified documents early, marketed discreetly, and we closed faster than we expected. Highly recommend for resale deals.',
                'rating' => 5,
                'image_url' => 'https://i.pravatar.cc/150?img=9',
                'sort_order' => 30,
            ],
            [
                'reviewer_name' => 'Vikram Iyer',
                'content' => 'Good experience exploring plotted developments near Devanahalli. PropUpdate explained RERA status and connectivity without overselling. The site visit was well organised and on time.',
                'rating' => 4,
                'image_url' => 'https://i.pravatar.cc/150?img=15',
                'sort_order' => 40,
            ],
            [
                'reviewer_name' => 'Meera Kulkarni',
                'content' => 'As a first-time buyer in Sarjapur Road, I appreciated how they broke down floor plans, payment schedules, and builder track records. Felt like talking to advisors, not just agents.',
                'rating' => 5,
                'image_url' => 'https://i.pravatar.cc/150?img=20',
                'sort_order' => 50,
            ],
            [
                'reviewer_name' => 'Arjun Desai',
                'content' => 'Used PropUpdate for a new launch project in East Bangalore. Brochure requests, callback timing, and follow-ups were professional. Would use them again for investment property research.',
                'rating' => 5,
                'image_url' => 'https://i.pravatar.cc/150?img=33',
                'sort_order' => 60,
            ],
        ];

        foreach ($rows as $row) {
            ClientReview::query()->updateOrCreate(
                ['reviewer_name' => $row['reviewer_name']],
                [
                    'content' => $row['content'],
                    'rating' => $row['rating'],
                    'image_url' => $row['image_url'],
                    'image_path' => null,
                    'sort_order' => $row['sort_order'],
                    'is_published' => true,
                ]
            );
        }
    }
}
