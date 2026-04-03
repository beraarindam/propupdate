<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.index');
})->name('home');

Route::get('/about-us', [PagesController::class, 'about'])->name('pages.about');

Route::get('/contact', [PagesController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PagesController::class, 'contactSubmit'])->name('pages.contact.submit');

Route::get('/privacy-policy', [PagesController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms-and-conditions', [PagesController::class, 'terms'])->name('pages.terms');

Route::post('/lead/pre-register', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:120',
        'email' => 'required|email|max:255',
        'message' => 'nullable|string|max:4000',
    ]);

    return redirect()
        ->route('home')
        ->fragment('pre-register')
        ->with('pre_register_status', 'Thank you — we\'ll be in touch with launch access and updates.');
})->name('lead.pre-register');
