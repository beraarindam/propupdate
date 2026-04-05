<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        if (! DB::table('pages')->where('slug', 'properties')->exists()) {
            DB::table('pages')->insert([
                'slug' => 'properties',
                'name' => 'Properties',
                'meta_title' => 'Browse properties — PropUpdate Realty',
                'meta_description' => 'Search resale and rental listings in Bangalore — filter by deal type, city, category, and more.',
                'meta_keywords' => 'Bangalore properties, resale, rent, PropUpdate listings',
                'banner_title' => 'Properties',
                'banner_lead' => 'Refine by <strong>deal type</strong>, location, and size — then explore listings tailored to you.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
                'banner_image_path' => null,
                'body_html' => null,
                'extras' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        if (! DB::table('pages')->where('slug', 'projects')->exists()) {
            DB::table('pages')->insert([
                'slug' => 'projects',
                'name' => 'Projects',
                'meta_title' => 'New launch projects — PropUpdate Realty',
                'meta_description' => 'Explore new launches and developments — pricing, location, and project story in one place.',
                'meta_keywords' => 'Bangalore new launches, projects, PropUpdate',
                'banner_title' => 'Projects',
                'banner_lead' => 'New launches and developments — <strong>pricing</strong>, location, and story in one place.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1920&q=80',
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
        DB::table('pages')->whereIn('slug', ['properties', 'projects'])->delete();
    }
};
