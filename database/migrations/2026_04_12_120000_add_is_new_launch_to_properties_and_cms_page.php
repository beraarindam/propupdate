<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('is_new_launch')->default(false)->after('is_featured');
        });

        if (! DB::table('pages')->where('slug', 'new-launches')->exists()) {
            $now = now();
            DB::table('pages')->insert([
                'slug' => 'new-launches',
                'name' => 'New launches',
                'meta_title' => 'New launches — PropUpdate Realty',
                'meta_description' => 'Explore new launch projects and listings in Bangalore — curated by PropUpdate Realty.',
                'meta_keywords' => 'PropUpdate, new launch, Bangalore, new projects',
                'banner_title' => 'New launches',
                'banner_lead' => 'Hand-picked <strong>new launch</strong> listings — also visible in the main properties directory.',
                'banner_image_url' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?auto=format&fit=crop&w=1920&q=80',
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
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('is_new_launch');
        });

        DB::table('pages')->where('slug', 'new-launches')->delete();
    }
};
