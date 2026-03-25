<?php
// routes/web.php
use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\Cours\Create as CreateCour;
use App\Livewire\Cours\EditCour;
use App\Livewire\Cours\ManageCours;
use App\Livewire\Cours\Enroll;
use App\Livewire\Dashboard\Teacher as TeacherDashboard;
use App\Livewire\Dashboard\Student as StudentDashboard;
use App\Livewire\Dashboard\Admin as AdminDashboard;
use App\Livewire\Announcements\Create as CreateAnnouncement;
use App\Livewire\Chapters\Create as CreateChapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/login', Login::class)->middleware('guest')->name('login');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/dashboard/admin', AdminDashboard::class)->name('dashboard.admin');
});

Route::middleware(['auth', 'can:teacher'])->group(function () {
    Route::get('/dashboard/teacher', TeacherDashboard::class)->name('dashboard.teacher');
    Route::get('/cours', ManageCours::class)->name('cours.index');
    Route::get('/cours/create', CreateCour::class)->name('cours.create');
    Route::get('/cours/{cour}/edit', EditCour::class)->name('cours.edit');
    Route::get('/cours/{cour}/announcements/create', CreateAnnouncement::class)->name('announcements.create');
    Route::get('/cours/{cour}/chapters/create', CreateChapter::class)->name('chapters.create');
});

Route::middleware(['auth', 'can:student'])->group(function () {
    Route::get('/dashboard/student', StudentDashboard::class)->name('dashboard.student');
    // Route::get('/cours/{cour}/enroll', Enroll::class)->name('cours.enroll');
});
