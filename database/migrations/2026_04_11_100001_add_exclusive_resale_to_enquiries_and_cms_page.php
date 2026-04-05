<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreignId('exclusive_resale_listing_id')
                ->nullable()
                ->after('property_id')
                ->constrained('exclusive_resale_listings')
                ->nullOnDelete();
        });

        $now = now();
        if (! DB::table('pages')->where('slug', 'exclusive-resale')->exists()) {
            DB::table('pages')->insert([
                'slug' => 'exclusive-resale',
                'name' => 'Exclusive resale',
                'meta_title' => 'Exclusive resale listings — PropUpdate Realty',
                'meta_description' => 'Hand-picked resale inventory in Bangalore — verified titles, transparent pricing, and direct advisory.',
                'meta_keywords' => 'exclusive resale, Bangalore, PropUpdate, below market',
                'banner_title' => 'Exclusive resale',
                'banner_lead' => 'Curated <strong>resale</strong> opportunities — verified inventory, clear numbers, and straight answers.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1920&q=80',
                'banner_image_path' => null,
                'body_html' => null,
                'extras' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('exclusive_resale_listing_id');
        });

        DB::table('pages')->where('slug', 'exclusive-resale')->delete();
    }
};
