<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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
        Vite::prefetch(concurrency: 3);

        Gate::define('admin', function(User $user) {
            return $user->role === 1;
        });

        Gate::define('manager-higher', function(User $user) {
            return $user->role > 0 && $user->role <= 5;
        });

        Gate::define('user-higher', function(User $user) {
            return $user->role > 0 && $user->role <= 9;
        });
    }
}
