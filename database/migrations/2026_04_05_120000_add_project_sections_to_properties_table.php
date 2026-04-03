<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('developer_name', 255)->nullable()->after('possession_status');
            $table->string('rera_number', 120)->nullable()->after('developer_name');
            $table->text('developer_description')->nullable()->after('rera_number');
            $table->string('project_land_area', 120)->nullable()->after('developer_description');
            $table->unsignedInteger('total_units')->nullable()->after('project_land_area');
            $table->string('towers_blocks_summary', 500)->nullable()->after('total_units');
            $table->string('unit_variants_summary', 120)->nullable()->after('towers_blocks_summary');
            $table->string('maps_link_url', 2000)->nullable()->after('unit_variants_summary');
            $table->text('price_disclaimer')->nullable()->after('maps_link_url');
            $table->json('configuration_rows')->nullable()->after('price_disclaimer');
            $table->json('unit_mix')->nullable()->after('configuration_rows');
            $table->json('specifications')->nullable()->after('unit_mix');
            $table->json('expert_pros')->nullable()->after('specifications');
            $table->json('expert_cons')->nullable()->after('expert_pros');
            $table->json('project_faqs')->nullable()->after('expert_cons');
            $table->string('master_plan_path', 255)->nullable()->after('project_faqs');
            $table->json('floor_plan_paths')->nullable()->after('master_plan_path');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'developer_name',
                'rera_number',
                'developer_description',
                'project_land_area',
                'total_units',
                'towers_blocks_summary',
                'unit_variants_summary',
                'maps_link_url',
                'price_disclaimer',
                'configuration_rows',
                'unit_mix',
                'specifications',
                'expert_pros',
                'expert_cons',
                'project_faqs',
                'master_plan_path',
                'floor_plan_paths',
            ]);
        });
    }
};
