<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Support\FooterGallery;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Schema::defaultStringLength(191);

        View::composer('frontend.layouts.master', function ($view): void {
            if (! Schema::hasTable('site_settings')) {
                $view->with('siteSettings', null);
            } else {
                $view->with('siteSettings', SiteSetting::query()->first());
            }

            $view->with('footerGalleryItems', FooterGallery::latestItems());
        });
    }
}
