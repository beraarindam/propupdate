<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('promo_popup_enabled')->default(false)->after('footer_text');
            $table->string('promo_popup_image_path', 255)->nullable()->after('promo_popup_enabled');
            $table->text('promo_popup_image_url')->nullable()->after('promo_popup_image_path');
            $table->string('promo_popup_link_url', 500)->nullable()->after('promo_popup_image_url');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'promo_popup_enabled',
                'promo_popup_image_path',
                'promo_popup_image_url',
                'promo_popup_link_url',
            ]);
        });
    }
};
