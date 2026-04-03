<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_category_id')->nullable()->constrained('property_categories')->nullOnDelete();
            $table->foreignId('property_type_id')->nullable()->constrained('property_types')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('listing_type', 20)->default('sale');
            $table->decimal('price', 18, 2)->nullable();
            $table->string('price_currency', 10)->default('INR');
            $table->boolean('price_on_request')->default(false);
            $table->string('maintenance_charges', 120)->nullable();
            $table->decimal('bedrooms', 5, 1)->nullable();
            $table->decimal('bathrooms', 5, 1)->nullable();
            $table->unsignedSmallInteger('balconies')->nullable();
            $table->unsignedSmallInteger('parking_covered')->nullable();
            $table->decimal('built_up_area_sqft', 12, 2)->nullable();
            $table->decimal('carpet_area_sqft', 12, 2)->nullable();
            $table->decimal('plot_area_sqft', 12, 2)->nullable();
            $table->smallInteger('floor_number')->nullable();
            $table->unsignedSmallInteger('total_floors')->nullable();
            $table->string('facing', 60)->nullable();
            $table->string('furnishing', 60)->nullable();
            $table->unsignedSmallInteger('age_of_property_years')->nullable();
            $table->string('possession_status', 120)->nullable();
            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->string('locality', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 120)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->json('amenities')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('featured_image_path', 255)->nullable();
            $table->text('featured_image_url')->nullable();
            $table->json('gallery_paths')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
            $table->index(['is_featured', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
