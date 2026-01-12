<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PropertyController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\AgentController;
use App\Http\Controllers\Web\AgentsController;
use App\Http\Controllers\Web\LeadController;
use App\Http\Controllers\Web\SubscriptionController as WebSubscriptionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\FeatureController as AdminFeatureController;
use App\Http\Controllers\Admin\SubscriptionPlanController as AdminSubscriptionPlanController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\BoostPackageController as AdminBoostPackageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// About page
Route::get('/about', function () {
    return view('web.about');
})->name('about');

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

        // Agent leads management
        Route::get('/my-leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/my-leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::put('/my-leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.update-status');

        // Agent subscription management
        Route::get('/subscription', [WebSubscriptionController::class, 'index'])->name('subscription.index');
        Route::get('/subscription/plans/{plan}', [WebSubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::post('/subscription/plans/{plan}', [WebSubscriptionController::class, 'submitSubscription'])->name('subscription.submit');
        Route::get('/subscription/buy-credits', [WebSubscriptionController::class, 'buyCredits'])->name('subscription.buy-credits');
        Route::post('/subscription/buy-credits', [WebSubscriptionController::class, 'submitCreditPurchase'])->name('subscription.submit-credits');
        Route::get('/subscription/boost/{property}', [WebSubscriptionController::class, 'boostProperty'])->name('subscription.boost');
        Route::post('/subscription/boost/{property}', [WebSubscriptionController::class, 'submitBoost'])->name('subscription.submit-boost');
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

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Features management
    Route::resource('features', AdminFeatureController::class);
    Route::post('/features/{feature}/toggle-active', [AdminFeatureController::class, 'toggleActive'])->name('features.toggle-active');

    // ===== SaaS Management Routes =====

    // Subscription Plans
    Route::resource('subscription-plans', AdminSubscriptionPlanController::class);
    Route::post('/subscription-plans/{subscription_plan}/toggle-active', [AdminSubscriptionPlanController::class, 'toggleActive'])->name('subscription-plans.toggle-active');

    // Subscriptions
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('/subscriptions/{subscription}/approve', [AdminSubscriptionController::class, 'approve'])->name('subscriptions.approve');
    Route::post('/subscriptions/{subscription}/reject', [AdminSubscriptionController::class, 'reject'])->name('subscriptions.reject');
    Route::post('/subscriptions/{subscription}/extend', [AdminSubscriptionController::class, 'extend'])->name('subscriptions.extend');
    Route::post('/subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    // Transactions
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/confirm', [AdminTransactionController::class, 'confirm'])->name('transactions.confirm');
    Route::post('/transactions/{transaction}/reject', [AdminTransactionController::class, 'reject'])->name('transactions.reject');
    Route::post('/transactions/{transaction}/refund', [AdminTransactionController::class, 'refund'])->name('transactions.refund');

    // Boost Packages
    Route::resource('boost-packages', AdminBoostPackageController::class);
    Route::post('/boost-packages/{boost_package}/toggle-active', [AdminBoostPackageController::class, 'toggleActive'])->name('boost-packages.toggle-active');
});


