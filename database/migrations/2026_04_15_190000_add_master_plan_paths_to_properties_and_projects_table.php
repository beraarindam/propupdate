<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (! Schema::hasColumn('properties', 'master_plan_paths')) {
                $table->json('master_plan_paths')->nullable()->after('master_plan_path');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (! Schema::hasColumn('projects', 'master_plan_paths')) {
                $table->json('master_plan_paths')->nullable()->after('master_plan_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'master_plan_paths')) {
                $table->dropColumn('master_plan_paths');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'master_plan_paths')) {
                $table->dropColumn('master_plan_paths');
            }
        });
    }
};
