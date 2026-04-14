<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('property_category_id')
                ->nullable()
                ->after('id')
                ->constrained('property_categories')
                ->nullOnDelete();
            $table->foreignId('property_area_id')
                ->nullable()
                ->after('property_category_id')
                ->constrained('property_areas')
                ->nullOnDelete();
            $table->string('address_line1', 255)->nullable()->after('location');
            $table->string('address_line2', 255)->nullable()->after('address_line1');
            $table->string('locality', 120)->nullable()->after('address_line2');
            $table->string('city', 120)->nullable()->after('locality');
            $table->string('state', 120)->nullable()->after('city');
            $table->string('postal_code', 20)->nullable()->after('state');
            $table->string('country', 120)->nullable()->after('postal_code');
            $table->decimal('latitude', 10, 7)->nullable()->after('country');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['property_category_id']);
            $table->dropForeign(['property_area_id']);
            $table->dropColumn([
                'property_category_id',
                'property_area_id',
                'address_line1',
                'address_line2',
                'locality',
                'city',
                'state',
                'postal_code',
                'country',
                'latitude',
                'longitude',
            ]);
        });
    }
};

