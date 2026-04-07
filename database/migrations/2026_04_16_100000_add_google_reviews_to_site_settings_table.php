<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('google_reviews_enabled')->default(false)->after('promo_popup_link_url');
            $table->string('google_place_id', 512)->nullable()->after('google_reviews_enabled');
            $table->text('google_places_api_key')->nullable()->after('google_place_id');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'google_reviews_enabled',
                'google_place_id',
                'google_places_api_key',
            ]);
        });
    }
};
