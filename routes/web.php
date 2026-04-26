<?php

use App\Interfaces\Http\Controllers\Auth\SocialController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware(['guest'])->group(function () {
    Route::livewire('/', 'pages::global.home.index')->name('home');
    Route::livewire('/programs', 'pages::global.programs.index')->name('programs');
    Route::livewire('/programs/{program}', 'pages::global.programs.show')->name('programs.show');
    Route::livewire('/about', 'pages::global.about.index')->name('about');
    Route::livewire('/pricing', 'pages::global.pricing.index')->name('pricing');
    Route::livewire('/careers', 'pages::global.careers.index')->name('careers');
    Route::livewire('/contact', 'pages::global.contact.index')->name('contact');

    // Debug Page for Loader
    Route::view('/preview-loader', 'preview-loader')->name('loader.preview');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::livewire('users', 'pages::users.index')->name('users.index');
        Route::livewire('specializations', 'pages::staff-dashboard.specializations.index')->name('specializations.index');
        Route::livewire('job-applications', 'pages::staff-dashboard.recruitment.index')->name('job-applications.index');

        // Phase 2 - Program System
        Route::livewire('programs-management', 'pages::staff-dashboard.programs.index')->name('programs.management');
        Route::livewire('programs-management/control/{program:slug?}', 'pages::staff-dashboard.programs.program-control')->name('programs.control');
        Route::livewire('programs-management/view/{program:slug}', 'pages::staff-dashboard.programs.view-program')->name('programs.view');
        Route::livewire('programs-management/archive', 'pages::staff-dashboard.programs.archive')->name('programs.archive');
        Route::livewire('bundles-management', 'pages::staff-dashboard.bundles.index')->name('bundles.management');
        Route::livewire('bundles-management/control/{bundle?}', 'pages::staff-dashboard.bundles.bundle-control')->name('bundles.control');
        Route::livewire('academy-schedule', 'pages::staff-dashboard.schedule.index')->name('schedule.management');
    });
});

// Social Login
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])->name('social.callback');

require __DIR__ . '/settings.php';
