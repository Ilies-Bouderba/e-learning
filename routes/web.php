<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\Enroll;
use App\Livewire\Cours\Create;
use App\Livewire\Cours\EditCour;
use App\Livewire\Cours\ManageCours;
use App\Livewire\Cours\StudentCourses;
use App\Livewire\Cours\Show;
use App\Livewire\Dashboard\Teacher;
use App\Livewire\Dashboard\Student;
use App\Livewire\Dashboard\Admin;
use App\Livewire\Announcements\Create as AnnouncementCreate;
use App\Livewire\Announcements\Index as AnnouncementIndex;
use App\Livewire\Chapters\Create as ChapterCreate;
use App\Livewire\Chapters\Show as ChapterShow;
use App\Livewire\Chapters\Edit as ChapterEdit;
use App\Livewire\Admin\ManageTeachers;
use App\Livewire\Admin\ManageStudents;
use App\Livewire\Admin\ManageDepartments;
use App\Livewire\Quizzes\Index as QuizIndex;
use App\Livewire\Quizzes\Create as QuizCreate;
use App\Livewire\Quizzes\Edit as QuizEdit;
use App\Livewire\Quizzes\Show as QuizShow;
use App\Livewire\Quizzes\Take as QuizTake;
use App\Livewire\Quizzes\StudentQuizIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ========== PUBLIC ROUTES ==========
Route::get('/', Home::class)->name('home');
Route::get('/login', Login::class)->middleware('guest')->name('login');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

// ========== AUTHENTICATED REDIRECT HELPERS ==========
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }
        return redirect()->route('student.dashboard');
    })->name('dashboard');

    Route::get('/cours', function () {
        $user = auth()->user();
        if ($user->isTeacher()) {
            return redirect()->route('teacher.cours.index');
        }
        return redirect()->route('student.cours.index');
    })->name('cours.index');
});

// ========== SHARED VIEW ROUTES (accessible by all authenticated users) ==========
Route::middleware('auth')->group(function () {
    Route::get('/cours/{cour}', Show::class)->name('cours.show');
    Route::get('/cours/{cour}/chapters/{chapter}', ChapterShow::class)->name('chapters.show');
    Route::get('/cours/{cour}/announcements', AnnouncementIndex::class)->name('announcements.index');
    Route::get('/cours/{cour}/quizzes/{quiz}', QuizShow::class)->name('quizzes.show');
});

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Admin::class)->name('dashboard');
    Route::get('/teachers', ManageTeachers::class)->name('teachers');
    Route::get('/students', ManageStudents::class)->name('students');
    Route::get('/departments', ManageDepartments::class)->name('departments');
});

// ========== TEACHER ROUTES ==========
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', Teacher::class)->name('dashboard');
    Route::get('/cours', ManageCours::class)->name('cours.index');
    Route::get('/cours/create', Create::class)->name('cours.create');
    Route::get('/cours/{cour}/edit', EditCour::class)->name('cours.edit');
    Route::get('/cours/{cour}/chapters/create', ChapterCreate::class)->name('chapters.create');
    Route::get('/cours/{cour}/chapters/{chapter}/edit', ChapterEdit::class)->name('chapters.edit');
    Route::get('/cours/{cour}/announcements/create', AnnouncementCreate::class)->name('announcements.create');
    Route::get('/cours/{cour}/quizzes', QuizIndex::class)->name('quizzes.index');
    Route::get('/cours/{cour}/quizzes/create', QuizCreate::class)->name('quizzes.create');
    Route::get('/cours/{cour}/quizzes/{quiz}/edit', QuizEdit::class)->name('quizzes.edit');
});

// ========== STUDENT ROUTES ==========
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', Student::class)->name('dashboard');
    Route::get('/cours', StudentCourses::class)->name('cours.index');
    Route::get('/cours/{cour}/enroll', Enroll::class)->name('cours.enroll');
    Route::get('/cours/{cour}/quizzes', StudentQuizIndex::class)->name('quizzes.index');
    Route::get('/cours/{cour}/quizzes/{quiz}/take', QuizTake::class)->name('quizzes.take');
});
