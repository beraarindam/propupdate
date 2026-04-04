<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PropertyListingController;
use App\Models\Enquiry;
use App\Models\Page;
use App\Models\PropertyCategory;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $homeCategories = PropertyCategory::query()
        ->where('is_published', true)
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->limit(4)
        ->get();

    if ($homeCategories->isEmpty()) {
        $homeCategories = PropertyCategory::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(4)
            ->get();
    }

    return view('frontend.index', [
        'page' => Page::bySlug('home'),
        'homeCategories' => $homeCategories,
        'services' => Service::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(),
    ]);
})->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/properties', [PropertyListingController::class, 'index'])->name('properties.index');
Route::post('/properties/{property}/enquiry', [PropertyListingController::class, 'submitEnquiry'])
    ->middleware('throttle:20,1')
    ->name('properties.enquiry');
Route::get('/properties/{property}', [PropertyListingController::class, 'show'])->name('properties.show');

Route::get('/about-us', [PagesController::class, 'about'])->name('pages.about');

Route::get('/contact', [PagesController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PagesController::class, 'contactSubmit'])->name('pages.contact.submit');

Route::get('/privacy-policy', [PagesController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms-and-conditions', [PagesController::class, 'terms'])->name('pages.terms');

Route::post('/lead/pre-register', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:120',
        'email' => 'required|email|max:255',
        'message' => 'nullable|string|max:4000',
    ]);

    Enquiry::create([
        'source' => Enquiry::SOURCE_PRE_REGISTER,
        'name' => $data['name'],
        'email' => $data['email'],
        'message' => $data['message'] ?? null,
        'ip_address' => $request->ip(),
    ]);

    return redirect()
        ->route('home')
        ->fragment('pre-register')
        ->with('pre_register_status', 'Thank you — we\'ll be in touch with launch access and updates.');
})->name('lead.pre-register');
