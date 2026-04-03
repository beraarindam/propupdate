<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('banner_title')->nullable();
            $table->text('banner_lead')->nullable();
            $table->text('banner_image_url')->nullable();
            $table->longText('body_html')->nullable();
            $table->json('extras')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $now = now();
        $homeExtras = [
            'hero' => [
                'line1' => 'Update your property search with',
                'line2' => 'PropUpdate Realty',
                'subtitle' => 'where decisions are informed, not influenced',
                'bg_url' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?auto=format&fit=crop&w=1920&q=80',
                'search_placeholder' => 'Location | Project | Builder',
            ],
        ];

        $rows = [
            [
                'slug' => 'home',
                'name' => 'Home',
                'meta_title' => 'PropUpdate Realty — Bangalore resale & new launches',
                'meta_description' => 'PropUpdate Realty helps you buy the right property in Bangalore with transparent guidance on resale, new launches, and investments.',
                'meta_keywords' => 'PropUpdate, Bangalore real estate, resale, new launch, property',
                'banner_title' => null,
                'banner_lead' => null,
                'banner_image_url' => null,
                'body_html' => null,
                'extras' => json_encode($homeExtras),
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'about-us',
                'name' => 'About us',
                'meta_title' => 'About PropUpdate Realty',
                'meta_description' => 'Learn how PropUpdate Realty serves buyers and investors in Bangalore with transparent resale and launch guidance.',
                'meta_keywords' => 'About PropUpdate, Bangalore realtor, property advisory',
                'banner_title' => 'About PropUpdate',
                'banner_lead' => 'Where every property decision is <strong>informed</strong>, not influenced — serving serious buyers and investors across Bangalore.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80',
                'body_html' => null,
                'extras' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'contact',
                'name' => 'Contact us',
                'meta_title' => 'Contact PropUpdate Realty',
                'meta_description' => 'Contact PropUpdate for resale, new launches, and investment property enquiries in Bangalore.',
                'meta_keywords' => 'Contact PropUpdate, Bangalore property enquiry',
                'banner_title' => 'Contact us',
                'banner_lead' => 'Questions about <strong>resale</strong>, <strong>launches</strong>, or <strong>investments</strong>? We reply within one business day.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?auto=format&fit=crop&w=1920&q=80',
                'body_html' => '<p>Share your brief — budget, locality, timeline — and we’ll route you to the right specialist.</p>',
                'extras' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'privacy-policy',
                'name' => 'Privacy policy',
                'meta_title' => 'Privacy policy — PropUpdate Realty',
                'meta_description' => 'How PropUpdate Realty collects, uses, and protects your personal information.',
                'meta_keywords' => 'privacy policy, PropUpdate, data protection',
                'banner_title' => 'Privacy policy',
                'banner_lead' => 'How PropUpdate Realty collects, uses, and protects your <strong>personal information</strong> on this website.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1920&q=80',
                'body_html' => null,
                'extras' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'terms-and-conditions',
                'name' => 'Terms & conditions',
                'meta_title' => 'Terms & conditions — PropUpdate Realty',
                'meta_description' => 'Rules for using the PropUpdate Realty website and information services.',
                'meta_keywords' => 'terms, conditions, PropUpdate',
                'banner_title' => 'Terms & conditions',
                'banner_lead' => 'Rules for using this website and our <strong>information services</strong>. Please read before you submit enquiries or rely on published content.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1920&q=80',
                'body_html' => null,
                'extras' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('pages')->insert($row);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
