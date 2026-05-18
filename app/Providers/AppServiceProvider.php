<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Quiz;
use App\Models\Exam;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Route::model('course', Course::class);
        Route::model('chapter', Chapter::class);
        Route::model('quiz', Quiz::class);
        Route::model('exam', Exam::class);

        Gate::define('admin',   fn ($user) => $user->role === 'admin');
        Gate::define('teacher', fn ($user) => $user->role === 'teacher');
        Gate::define('student', fn ($user) => $user->role === 'student');
    }
}
