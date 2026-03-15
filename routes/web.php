<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Teacher as TeacherDashboard;
use App\Livewire\Dashboard\Student as StudentDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

Route::get('/dashboard/teacher', TeacherDashboard::class)->middleware(['auth', 'role:teacher'])->name('dashboard.teacher');
Route::get('/dashboard/student', StudentDashboard::class)->middleware(['auth', 'role:student'])->name('dashboard.student');

