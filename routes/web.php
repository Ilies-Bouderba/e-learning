<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;

use App\Livewire\Cours\Create      as CourseCreate;
use App\Livewire\Cours\Edit        as CourseEdit;
use App\Livewire\Cours\Manage      as CourseManage;
use App\Livewire\Cours\Show        as CourseShow;
use App\Livewire\Cours\StudentCourses;
use App\Livewire\Cours\Enroll;

use App\Livewire\Chapters\Create   as ChapterCreate;
use App\Livewire\Chapters\Edit     as ChapterEdit;
use App\Livewire\Chapters\Show     as ChapterShow;

use App\Livewire\Announcements\Create        as AnnouncementCreate;
use App\Livewire\Announcements\Index         as AnnouncementIndex;
use App\Livewire\Announcements\StudentIndex  as StudentAnnouncementIndex;

use App\Livewire\Quizzes\Create       as QuizCreate;
use App\Livewire\Quizzes\Edit         as QuizEdit;
use App\Livewire\Quizzes\Index        as QuizIndex;
use App\Livewire\Quizzes\Show         as QuizShow;
use App\Livewire\Quizzes\Take         as QuizTake;
use App\Livewire\Quizzes\StudentIndex as StudentQuizIndex;

use App\Livewire\Exams\Create       as ExamCreate;
use App\Livewire\Exams\Edit         as ExamEdit;
use App\Livewire\Exams\Index        as ExamIndex;
use App\Livewire\Exams\Show         as ExamShow;
use App\Livewire\Exams\Take         as ExamTake;
use App\Livewire\Exams\StudentIndex as StudentExamIndex;

use App\Livewire\Dashboard\Admin   as AdminDashboard;
use App\Livewire\Dashboard\Teacher as TeacherDashboard;
use App\Livewire\Dashboard\Student as StudentDashboard;

use App\Livewire\Admin\ManageTeachers;
use App\Livewire\Admin\ManageStudents;
use App\Livewire\Admin\ManageDepartments;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════
// PUBLIC
// ═══════════════════════════════════════════════════════

Route::get('/', Home::class)->name('home');
Route::get('/login', Login::class)->middleware('guest')->name('login');
Route::get('/register', \App\Livewire\Auth\Register::class)->middleware('guest')->name('register');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

// ═══════════════════════════════════════════════════════
// SMART REDIRECTS
// ═══════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            default   => redirect()->route('student.dashboard'),
        };
    })->name('dashboard');

    Route::get('/cours', function () {
        return auth()->user()->isTeacher()
            ? redirect()->route('teacher.cours.index')
            : redirect()->route('student.cours.index');
    })->name('cours.index');
});

// ═══════════════════════════════════════════════════════
// SHARED — all roles, no role middleware
// Routes here handle auth internally in the component
// ═══════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {
    // Courses
    Route::get('/cours/{course}',                           CourseShow::class)->name('cours.show');

    // Chapters
    Route::get('/cours/{course}/chapters/create',           ChapterCreate::class)->name('chapters.create');
    Route::get('/cours/{course}/chapters/{chapter}',        ChapterShow::class)->name('chapters.show');
    Route::get('/cours/{course}/chapters/{chapter}/edit',   ChapterEdit::class)->name('chapters.edit');

    // Announcements
    Route::get('/cours/{course}/announcements',             AnnouncementIndex::class)->name('announcements.index');
    Route::get('/cours/{course}/announcements/create',      AnnouncementCreate::class)->name('announcements.create');

    // Quizzes
    Route::get('/cours/{course}/quizzes',                   QuizIndex::class)->name('quizzes.index');
    Route::get('/cours/{course}/quizzes/create',            QuizCreate::class)->name('quizzes.create');
    Route::get('/cours/{course}/quizzes/{quiz}',            QuizShow::class)->name('quizzes.show');
    Route::get('/cours/{course}/quizzes/{quiz}/edit',       QuizEdit::class)->name('quizzes.edit');
    Route::get('/cours/{course}/quizzes/{quiz}/take',       QuizTake::class)->name('quizzes.take');

    // Exams
    Route::get('/cours/{course}/exams',                     ExamIndex::class)->name('exams.index');
    Route::get('/cours/{course}/exams/create',              ExamCreate::class)->name('exams.create');
    Route::get('/cours/{course}/exams/{exam}',              ExamShow::class)->name('exams.show');
    Route::get('/cours/{course}/exams/{exam}/edit',         ExamEdit::class)->name('exams.edit');
    Route::get('/cours/{course}/exams/{exam}/take',         ExamTake::class)->name('exams.take');
});

// ═══════════════════════════════════════════════════════
// ADMIN
// ═══════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',   AdminDashboard::class)->name('dashboard');
    Route::get('/teachers',    ManageTeachers::class)->name('teachers');
    Route::get('/students',    ManageStudents::class)->name('students');
    Route::get('/departments', ManageDepartments::class)->name('departments');
});

// ═══════════════════════════════════════════════════════
// TEACHER
// ═══════════════════════════════════════════════════════

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard',   TeacherDashboard::class)->name('dashboard');
    Route::get('/cours',       CourseManage::class)->name('cours.index');
    Route::get('/cours/create', CourseCreate::class)->name('cours.create');
    Route::get('/cours/{course}/edit', CourseEdit::class)->name('cours.edit');

    // Teacher-prefixed aliases so teacher blade links work
    Route::get('/cours/{course}/chapters/create',           ChapterCreate::class)->name('chapters.create');
    Route::get('/cours/{course}/chapters/{chapter}/edit',   ChapterEdit::class)->name('chapters.edit');
    Route::get('/cours/{course}/announcements/create',      AnnouncementCreate::class)->name('announcements.create');
    Route::get('/cours/{course}/quizzes',                   QuizIndex::class)->name('quizzes.index');
    Route::get('/cours/{course}/quizzes/create',            QuizCreate::class)->name('quizzes.create');
    Route::get('/cours/{course}/quizzes/{quiz}/edit',       QuizEdit::class)->name('quizzes.edit');
    Route::get('/cours/{course}/exams',                     ExamIndex::class)->name('exams.index');
    Route::get('/cours/{course}/exams/create',              ExamCreate::class)->name('exams.create');
    Route::get('/cours/{course}/exams/{exam}/edit',         ExamEdit::class)->name('exams.edit');
});

// ═══════════════════════════════════════════════════════
// STUDENT
// ═══════════════════════════════════════════════════════

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard',                                    StudentDashboard::class)->name('dashboard');
    Route::get('/cours',                                        StudentCourses::class)->name('cours.index');
    Route::get('/cours/{course}/enroll',                        Enroll::class)->name('cours.enroll');
    Route::get('/cours/{course}/quizzes',                       StudentQuizIndex::class)->name('quizzes.index');
    Route::get('/cours/{course}/quizzes/{quiz}/take',           QuizTake::class)->name('quizzes.take');
    Route::get('/cours/{course}/exams',                         StudentExamIndex::class)->name('exams.index');
    Route::get('/cours/{course}/exams/{exam}/take',             ExamTake::class)->name('exams.take');
    Route::get('/announcements',                                StudentAnnouncementIndex::class)->name('all-announcements');
});
