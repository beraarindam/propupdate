<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
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
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
