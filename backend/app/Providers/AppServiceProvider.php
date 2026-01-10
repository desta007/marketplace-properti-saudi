<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define Gate for agents to create/manage properties
        Gate::define('create-property', function ($user) {
            // Allow if user is an agent (verified, pending, or rejected status)
            // This allows agents to see their properties even if suspended
            return $user->role === 'agent';
        });
    }
}
