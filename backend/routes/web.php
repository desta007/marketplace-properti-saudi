<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PropertyController;
use App\Http\Controllers\Web\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Properties
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Language switcher
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login/send-otp', [AuthController::class, 'sendOtp'])->name('login.send-otp');
    Route::post('/login/verify', [AuthController::class, 'verify'])->name('login.verify');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/favorites', [PropertyController::class, 'favorites'])->name('favorites');
    Route::post('/favorites/{property}', [PropertyController::class, 'toggleFavorite'])->name('favorites.toggle');
    
    // Agent routes
    Route::middleware('can:create-property')->group(function () {
        Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
        Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
        Route::get('/my-properties', [PropertyController::class, 'myProperties'])->name('my-properties');
    });
});
