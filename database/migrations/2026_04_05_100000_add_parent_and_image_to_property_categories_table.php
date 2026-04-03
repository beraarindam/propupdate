<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_categories', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained('property_categories')
                ->nullOnDelete();
            $table->string('image_path', 255)->nullable()->after('meta_keywords');
            $table->text('image_url')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('property_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'image_path', 'image_url']);
        });
    }
};
