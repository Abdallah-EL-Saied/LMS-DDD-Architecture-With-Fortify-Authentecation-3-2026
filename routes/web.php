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
    Route::livewire('/courses', 'pages::global.courses')->name('courses');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::livewire('users', 'pages::users.index')->name('users.index');
    });
});

// Social Login
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])->name('social.callback');

require __DIR__ . '/settings.php';
