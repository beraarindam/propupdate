<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('pages')->where('slug', 'blog')->exists()) {
            return;
        }

        $now = now();

        DB::table('pages')->insert([
            'slug' => 'blog',
            'name' => 'Blog',
            'meta_title' => 'Blog — PropUpdate Realty',
            'meta_description' => 'Insights on Bangalore real estate, launches, resale, and buying with clarity.',
            'meta_keywords' => 'PropUpdate, blog, Bangalore real estate, property news',
            'banner_title' => 'Blog',
            'banner_lead' => 'Insights on <strong>Bangalore real estate</strong>, launches, resale, and buying with clarity.',
            'banner_image_url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
            'banner_image_path' => null,
            'body_html' => null,
            'extras' => null,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('pages')->where('slug', 'blog')->delete();
    }
};
