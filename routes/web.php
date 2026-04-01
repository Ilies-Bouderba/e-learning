<?php

use App\Livewire\Admin\ManageDepartments;
use App\Livewire\Admin\ManageStudents;
use App\Livewire\Admin\ManageTeachers;
use App\Livewire\Announcements\Create as CreateAnnouncement;
use App\Livewire\Announcements\Index as AnnouncementsIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Chapters\Create as CreateChapter;
use App\Livewire\Chapters\Edit as EditChapter;
use App\Livewire\Chapters\Show as ShowChapter;
use App\Livewire\Cours\Create as CreateCour;
use App\Livewire\Cours\EditCour;
use App\Livewire\Cours\ManageCours;
use App\Livewire\Cours\Show as ShowCour;
use App\Livewire\Dashboard\Admin as AdminDashboard;
use App\Livewire\Dashboard\Student as StudentDashboard;
use App\Livewire\Dashboard\Teacher as TeacherDashboard;
use App\Livewire\Enroll;
use App\Livewire\Home;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', Home::class)->name('home');
Route::get('/login', Login::class)->middleware('guest')->name('login');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/login');
})->middleware('auth')->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', AdminDashboard::class)->name('dashboard.admin');
    Route::get('/admin/teachers', ManageTeachers::class)->name('admin.teachers');
    Route::get('/admin/students', ManageStudents::class)->name('admin.students');
    Route::get('/admin/departments', ManageDepartments::class)->name('admin.departments');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard/teacher', TeacherDashboard::class)->name('dashboard.teacher');
    Route::get('/cours', ManageCours::class)->name('cours.index');
    Route::get('/cours/create', CreateCour::class)->name('cours.create');
    Route::get('/cours/{cour}/edit', EditCour::class)->name('cours.edit');
    Route::get('/cours/{cour}/chapters/create', CreateChapter::class)->name('chapters.create');
    Route::get('/cours/{cour}/chapters/{chapter}/edit', EditChapter::class)->name('chapters.edit');
    Route::get('/cours/{cour}/announcements/create', CreateAnnouncement::class)->name('announcements.create');
    Route::get('/cours/{cour}/exams', function () {
        abort(501);
    })->name('exams.index');
    Route::get('/cours/{cour}/exams/create', function () {
        abort(501);
    })->name('exams.create');
});

// shared between the students and the teachers
Route::middleware('auth')->group(function () {
    Route::get('/cours/{cour}', ShowCour::class)->name('cours.show');
    Route::get('/cours/{cour}/chapters/{chapter}', ShowChapter::class)->name('chapters.show');
    Route::get('/cours/{cour}/announcements', AnnouncementsIndex::class)->name('announcements.index');
    Route::get('/cours/{cour}/exams', function () {
        abort(501);
    })->name('exams.index');
});

// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard/student', StudentDashboard::class)->name('dashboard.student');
    Route::get('/cours/{cour}/enroll', Enroll::class)->name('cours.enroll');
});
