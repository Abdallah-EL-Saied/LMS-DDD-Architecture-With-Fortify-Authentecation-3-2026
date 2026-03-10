<?php

use App\Interfaces\Http\Controllers\Auth\SocialController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home.index')->name('home');
Route::livewire('/courses', 'pages::home.courses')->name('courses');

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
