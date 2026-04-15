<?php

namespace App\Providers;

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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Gate para gerenciar deletar dados e admins
        \Illuminate\Support\Facades\Gate::define('manage-admins', function ($user) {
            return $user->email === 'admin@admin.com';
        });

        \Illuminate\Support\Facades\Gate::define('manage-data', function ($user) {
            return $user->role === 'admin' || $user->email === 'admin@admin.com';
        });

        \Illuminate\Support\Facades\View::share('last_deploy', now());
    }
}
