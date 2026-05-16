<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\Enroll;
use App\Livewire\Cours\Create as CreateCour;
use App\Livewire\Cours\EditCour;
use App\Livewire\Cours\ManageCours;
use App\Livewire\Cours\StudentCourses;
use App\Livewire\Cours\Show as ShowCour;
use App\Livewire\Dashboard\Teacher as TeacherDashboard;
use App\Livewire\Dashboard\Student as StudentDashboard;
use App\Livewire\Dashboard\Admin as AdminDashboard;
use App\Livewire\Announcements\Create as CreateAnnouncement;
use App\Livewire\Announcements\Index as AnnouncementsIndex;
use App\Livewire\Chapters\Create as CreateChapter;
use App\Livewire\Chapters\Show as ShowChapter;
use App\Livewire\Chapters\Edit as EditChapter;
use App\Livewire\Admin\ManageTeachers;
use App\Livewire\Admin\ManageStudents;
use App\Livewire\Admin\ManageDepartments;
use App\Livewire\Quizzes\Index as QuizIndex;
use App\Livewire\Quizzes\Create as QuizCreate;
use App\Livewire\Quizzes\Edit as QuizEdit;
use App\Livewire\Quizzes\Show as QuizShow;
use App\Livewire\Quizzes\Take as QuizTake;
use App\Livewire\Quizzes\StudentQuizIndex;
use App\Livewire\Exams\Index as ExamIndex;
use App\Livewire\Exams\Create as ExamCreate;
use App\Livewire\Exams\Edit as ExamEdit;
use App\Livewire\Exams\Show as ExamShow;
use App\Livewire\Exams\Take as ExamTake;
use App\Livewire\Announcements\StudentIndex as StudentAnnouncements;
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
    Route::get('/cours/{cour}', \App\Livewire\Cours\Show::class)->name('cours.show');
    Route::get('/cours/{cour}/chapters/{chapter}', \App\Livewire\Chapters\Show::class)->name('chapters.show');
    Route::get('/cours/{cour}/announcements', \App\Livewire\Announcements\Index::class)->name('announcements.index');
    Route::get('/cours/{cour}/quizzes/{quiz}', \App\Livewire\Quizzes\Show::class)->name('quizzes.show');
    Route::get('/cours/{cour}/exams/{exam}', \App\Livewire\Exams\Show::class)->name('exams.show');

    // STUDENT exam listing (view all exams to take)
    Route::get('/cours/{cour}/exams', \App\Livewire\Exams\StudentIndex::class)->name('exams.index');

    // STUDENT take exam
    Route::get('/cours/{cour}/exams/{exam}/take', \App\Livewire\Exams\Take::class)->name('exams.take');
});

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/teachers', ManageTeachers::class)->name('teachers');
    Route::get('/students', ManageStudents::class)->name('students');
    Route::get('/departments', ManageDepartments::class)->name('departments');
});

// ========== TEACHER ROUTES ==========
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard\Teacher::class)->name('dashboard');
    Route::get('/cours', \App\Livewire\Cours\ManageCours::class)->name('cours.index');
    Route::get('/cours/create', \App\Livewire\Cours\Create::class)->name('cours.create');
    Route::get('/cours/{cour}/edit', \App\Livewire\Cours\EditCour::class)->name('cours.edit');
    Route::get('/cours/{cour}/chapters/create', \App\Livewire\Chapters\Create::class)->name('chapters.create');
    Route::get('/cours/{cour}/chapters/{chapter}/edit', \App\Livewire\Chapters\Edit::class)->name('chapters.edit');
    Route::get('/cours/{cour}/announcements/create', \App\Livewire\Announcements\Create::class)->name('announcements.create');
    Route::get('/cours/{cour}/quizzes', \App\Livewire\Quizzes\Index::class)->name('quizzes.index');
    Route::get('/cours/{cour}/quizzes/create', \App\Livewire\Quizzes\Create::class)->name('quizzes.create');
    Route::get('/cours/{cour}/quizzes/{quiz}/edit', \App\Livewire\Quizzes\Edit::class)->name('quizzes.edit');

    // TEACHER exam management
    Route::get('/cours/{cour}/exams', \App\Livewire\Exams\Index::class)->name('exams.index');
    Route::get('/cours/{cour}/exams/create', \App\Livewire\Exams\Create::class)->name('exams.create');
    Route::get('/cours/{cour}/exams/{exam}/edit', \App\Livewire\Exams\Edit::class)->name('exams.edit');
});

// ========== STUDENT ROUTES ==========
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    Route::get('/cours', StudentCourses::class)->name('cours.index');
    Route::get('/cours/{cour}/enroll', Enroll::class)->name('cours.enroll');
    Route::get('/cours/{cour}/quizzes', StudentQuizIndex::class)->name('quizzes.index');
    Route::get('/cours/{cour}/exams', \App\Livewire\Exams\StudentIndex::class)->name('exams.index');
    Route::get('/cours/{cour}/quizzes/{quiz}/take', QuizTake::class)->name('quizzes.take');
    Route::get('/cours/{cour}/exams/{exam}/take', \App\Livewire\Exams\Take::class)->name('exams.take');
    Route::get('/announcements', StudentAnnouncements::class)->name('all-announcements');
});
