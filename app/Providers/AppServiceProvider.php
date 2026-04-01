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
        Gate::define('admin', fn ($user) => $user->role === 'admin');
        Gate::define('teacher', fn ($user) => $user->role === 'teacher');
        Gate::define('student', fn ($user) => $user->role === 'student');
    }
}
