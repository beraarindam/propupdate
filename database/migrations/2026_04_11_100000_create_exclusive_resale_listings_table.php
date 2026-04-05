<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exclusive_resale_listings', function (Blueprint $table) {
            $table->id();
            $table->string('property_code', 64)->nullable();
            $table->string('title');
            $table->string('status_badge', 120)->nullable();
            $table->string('location')->nullable();
            $table->string('property_type', 120)->nullable();
            $table->string('configuration', 120)->nullable();
            $table->string('area_display', 120)->nullable();
            $table->string('market_price', 120)->nullable();
            $table->string('asking_price', 120)->nullable();
            $table->string('rate_per_sqft', 120)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->text('image_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'sort_order', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exclusive_resale_listings');
    }
};
