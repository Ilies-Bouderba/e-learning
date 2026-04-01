<?php

namespace App\Livewire\Dashboard;

use App\Models\Cour;
use App\Models\Department;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Admin extends Component
{
    public function render()
    {
        return view('livewire.dashboard.admin', [
            'totalTeachers' => User::where('role', 'teacher')->count(),
            'totalStudents' => User::where('role', 'student')->count(),
            'totalCourses' => Cour::count(),
            'totalDepts' => Department::count(),
            'recentTeachers' => User::where('role', 'teacher')->withCount('courses')->latest()->take(5)->get(),
            'recentStudents' => User::where('role', 'student')->withCount('enrollments')->latest()->take(5)->get(),
            'recentCourses' => Cour::with(['teacher', 'department'])->withCount('enrollments')->latest()->take(6)->get(),
            'departments' => Department::withCount('courses')->get(),
        ]);
    }
}
