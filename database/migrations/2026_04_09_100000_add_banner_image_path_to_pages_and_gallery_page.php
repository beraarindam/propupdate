<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('banner_image_path', 255)->nullable()->after('banner_image_url');
        });

        $now = now();
        $exists = DB::table('pages')->where('slug', 'gallery')->exists();
        if (! $exists) {
            DB::table('pages')->insert([
                'slug' => 'gallery',
                'name' => 'Gallery',
                'meta_title' => 'Gallery — PropUpdate Realty',
                'meta_description' => 'Browse our gallery of projects, spaces, and places across Bangalore real estate.',
                'meta_keywords' => 'PropUpdate, gallery, Bangalore property photos',
                'banner_title' => 'Gallery',
                'banner_lead' => 'A curated look at <strong>projects</strong>, spaces, and places we work with.',
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
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('banner_image_path');
        });

        DB::table('pages')->where('slug', 'gallery')->delete();
    }
};
