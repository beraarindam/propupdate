<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ExclusiveResaleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropertyListingController;
use App\Models\Enquiry;
use App\Models\Page;
use App\Models\PropertyCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Support\GooglePlaceReviews;
use App\Support\LeadRatClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

    $googleReviews = null;
    if (Schema::hasTable('site_settings')) {
        $siteSettingsRow = SiteSetting::query()->first();
        if ($siteSettingsRow) {
            $googleReviews = GooglePlaceReviews::forHome($siteSettingsRow);
        }
    }

    return view('frontend.index', [
        'page' => Page::bySlug('home'),
        'homeCategories' => $homeCategories,
        'services' => Service::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(),
        'googleReviews' => $googleReviews,
    ]);
})->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/exclusive-resale', [ExclusiveResaleController::class, 'index'])->name('exclusive-resale.index');
Route::post('/exclusive-resale/{listing}/enquiry', [ExclusiveResaleController::class, 'submitEnquiry'])
    ->middleware('throttle:20,1')
    ->name('exclusive-resale.enquiry');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/projects/{project}/enquiry', [ProjectController::class, 'submitEnquiry'])
    ->middleware('throttle:20,1')
    ->name('projects.enquiry');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/properties', [PropertyListingController::class, 'index'])->name('properties.index');
Route::get('/properties/suggestions', [PropertyListingController::class, 'suggestions'])
    ->middleware('throttle:45,1')
    ->name('properties.suggestions');
Route::get('/new-launches', [PropertyListingController::class, 'newLaunches'])->name('new-launches.index');
Route::post('/properties/{property}/enquiry', [PropertyListingController::class, 'submitEnquiry'])
    ->middleware('throttle:20,1')
    ->name('properties.enquiry');
Route::post('/properties/{property}/brochure-request', [PropertyListingController::class, 'submitBrochureRequest'])
    ->middleware('throttle:20,1')
    ->name('properties.brochure-request');
Route::get('/properties/{property}', [PropertyListingController::class, 'show'])->name('properties.show');

Route::get('/about-us', [PagesController::class, 'about'])->name('pages.about');

Route::get('/contact', [PagesController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PagesController::class, 'contactSubmit'])->name('pages.contact.submit');

Route::get('/privacy-policy', [PagesController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms-and-conditions', [PagesController::class, 'terms'])->name('pages.terms');

Route::post('/lead/consultation', function (Request $request) {
    $data = $request->validateWithBag('consultation', [
        'consult_name' => 'required|string|max:120',
        'consult_email' => 'required|email|max:255',
        'consult_phone' => 'required|string|max:32',
        'consult_message' => 'required|string|max:4000',
    ]);

    $enquiry = Enquiry::create([
        'source' => Enquiry::SOURCE_CONTACT,
        'name' => $data['consult_name'],
        'email' => $data['consult_email'],
        'phone' => $data['consult_phone'],
        'subject' => 'Free consultation request',
        'message' => $data['consult_message'],
        'ip_address' => $request->ip(),
    ]);
    app(LeadRatClient::class)->pushFromEnquiry($enquiry, [
        'subsource' => 'Free Consultation',
        'leadStatus' => 'Consultation Requested',
        'page_url' => $request->fullUrl(),
    ]);

    return redirect()
        ->route('home')
        ->fragment('why-propupdate')
        ->with('consultation_status', 'Thank you — our team will call you shortly.');
})->middleware('throttle:20,1')->name('lead.consultation');

Route::post('/lead/pre-register', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:120',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:32',
        'message' => 'required|string|max:4000',
    ]);

    $enquiry = Enquiry::create([
        'source' => Enquiry::SOURCE_PRE_REGISTER,
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'message' => $data['message'],
        'ip_address' => $request->ip(),
    ]);
    app(LeadRatClient::class)->pushFromEnquiry($enquiry, [
        'subsource' => 'Pre Register',
        'leadStatus' => 'Pre Register Submitted',
        'page_url' => $request->fullUrl(),
    ]);

    return redirect()
        ->route('home')
        ->fragment('pre-register')
        ->with('pre_register_status', 'Thank you — we\'ll be in touch with launch access and updates.');
})->name('lead.pre-register');
