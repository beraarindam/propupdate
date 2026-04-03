<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PropertyCategoryController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('home');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('site-settings', [SiteSettingsController::class, 'index'])->name('site-settings');
    Route::post('site-settings', [SiteSettingsController::class, 'update'])->name('site-settings.update');
    Route::post('site-settings/logo/remove', [SiteSettingsController::class, 'destroyLogo'])->name('site-settings.logo.destroy');
    Route::post('site-settings/favicon/remove', [SiteSettingsController::class, 'destroyFavicon'])->name('site-settings.favicon.destroy');

    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');

    Route::get('faqs/data', [FaqController::class, 'data'])->name('faqs.data');
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::get('faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

    Route::get('blogs/data', [BlogPostController::class, 'data'])->name('blogs.data');
    Route::post('blogs/tinymce-upload', [BlogPostController::class, 'tinymceUpload'])->name('blogs.tinymce-upload');
    Route::get('blogs', [BlogPostController::class, 'index'])->name('blogs.index');
    Route::get('blogs/create', [BlogPostController::class, 'create'])->name('blogs.create');
    Route::post('blogs', [BlogPostController::class, 'store'])->name('blogs.store');
    Route::get('blogs/{blog}/edit', [BlogPostController::class, 'edit'])->name('blogs.edit');
    Route::put('blogs/{blog}', [BlogPostController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{blog}', [BlogPostController::class, 'destroy'])->name('blogs.destroy');

    Route::get('property-categories/data', [PropertyCategoryController::class, 'data'])->name('property_categories.data');
    Route::get('property-categories', [PropertyCategoryController::class, 'index'])->name('property_categories.index');
    Route::get('property-categories/create', [PropertyCategoryController::class, 'create'])->name('property_categories.create');
    Route::post('property-categories', [PropertyCategoryController::class, 'store'])->name('property_categories.store');
    Route::get('property-categories/{property_category}/edit', [PropertyCategoryController::class, 'edit'])->name('property_categories.edit');
    Route::put('property-categories/{property_category}', [PropertyCategoryController::class, 'update'])->name('property_categories.update');
    Route::delete('property-categories/{property_category}', [PropertyCategoryController::class, 'destroy'])->name('property_categories.destroy');

    Route::get('property-types/data', [PropertyTypeController::class, 'data'])->name('property_types.data');
    Route::get('property-types', [PropertyTypeController::class, 'index'])->name('property_types.index');
    Route::get('property-types/create', [PropertyTypeController::class, 'create'])->name('property_types.create');
    Route::post('property-types', [PropertyTypeController::class, 'store'])->name('property_types.store');
    Route::get('property-types/{property_type}/edit', [PropertyTypeController::class, 'edit'])->name('property_types.edit');
    Route::put('property-types/{property_type}', [PropertyTypeController::class, 'update'])->name('property_types.update');
    Route::delete('property-types/{property_type}', [PropertyTypeController::class, 'destroy'])->name('property_types.destroy');

    Route::get('properties/data', [PropertyController::class, 'data'])->name('properties.data');
    Route::post('properties/tinymce-upload', [PropertyController::class, 'tinymceUpload'])->name('properties.tinymce-upload');
    Route::get('properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');

    Route::get('services/data', [ServiceController::class, 'data'])->name('services.data');
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('enquiries/data', [EnquiryController::class, 'data'])->name('enquiries.data');
    Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
