<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PropertyController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\AgentController;
use App\Http\Controllers\Web\AgentsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\AgentController as AdminAgentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Properties (public)
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
// Note: /properties/new route is defined later in authenticated agent routes
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show')
    ->where('property', '[0-9]+'); // Only match numeric IDs

// Agents (public)
Route::get('/agents', [AgentsController::class, 'index'])->name('agents.index');
Route::get('/agents/{agent}', [AgentsController::class, 'show'])->name('agents.show');

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
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Favorites
    Route::get('/favorites', [PropertyController::class, 'favorites'])->name('favorites');
    Route::post('/favorites/{property}', [PropertyController::class, 'toggleFavorite'])->name('favorites.toggle');

    // Agent registration
    Route::get('/become-agent', [AgentController::class, 'showRegister'])->name('agent.register');
    Route::post('/become-agent', [AgentController::class, 'register'])->name('agent.register.submit');

    // Agent routes (property management)
    Route::middleware('can:create-property')->group(function () {
        Route::get('/my-properties', [PropertyController::class, 'myProperties'])->name('my-properties');
        Route::get('/properties/new', [PropertyController::class, 'create'])->name('properties.create');
        Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
        Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
        Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Properties moderation
    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}', [AdminPropertyController::class, 'show'])->name('properties.show');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('properties.reject');

    // Agents verification
    Route::get('/agents', [AdminAgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/{agent}', [AdminAgentController::class, 'show'])->name('agents.show');
    Route::post('/agents/{agent}/verify', [AdminAgentController::class, 'verify'])->name('agents.verify');
    Route::post('/agents/{agent}/reject', [AdminAgentController::class, 'reject'])->name('agents.reject');
    Route::post('/agents/{agent}/suspend', [AdminAgentController::class, 'suspend'])->name('agents.suspend');
});

