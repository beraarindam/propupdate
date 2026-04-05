<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('extras')->nullable()->after('body');
            $table->text('maps_link_url')->nullable()->after('developer_name');
            $table->string('rera_number', 120)->nullable()->after('maps_link_url');
            $table->json('gallery_paths')->nullable()->after('featured_image_url');
            $table->string('master_plan_path', 255)->nullable()->after('gallery_paths');
            $table->json('floor_plan_paths')->nullable()->after('master_plan_path');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'extras',
                'maps_link_url',
                'rera_number',
                'gallery_paths',
                'master_plan_path',
                'floor_plan_paths',
            ]);
        });
    }
};
