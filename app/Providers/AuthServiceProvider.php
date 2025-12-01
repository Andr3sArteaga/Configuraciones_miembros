<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for menu visibility based on user role
        
        // Admin-only resources
        Gate::define('viewAny', function ($user, $modelClass) {
            return $user->isAdmin();
        });
        
        Gate::define('create', function ($user, $modelClass) {
            return $user->isAdmin();
        });
        
        Gate::define('update', function ($user, $modelClass) {
            return $user->isAdmin();
        });
        
        Gate::define('delete', function ($user, $modelClass) {
            return $user->isAdmin();
        });
        
        // Menu visibility gates
        Gate::define('admin-only', function ($user) {
            return $user->isAdmin();
        });
        
        Gate::define('user-only', function ($user) {
            return !$user->isAdmin();
        });
        
        Gate::before(function ($user, $ability) {
            // guest-only is handled by the absence of authentication
            if ($ability === 'guest-only') {
                return false; // Authenticated users cannot access guest-only items
            }
        });
    }
}
