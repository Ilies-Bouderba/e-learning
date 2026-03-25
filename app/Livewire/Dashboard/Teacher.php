<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Teacher extends Component
{
    public function render()
    {
        $courses = auth()->user()->courses()->latest()->take(3)->get();
        $totalCourses = auth()->user()->courses()->count();

        return view('livewire.dashboard.teacher', compact('courses', 'totalCourses'));
    }
}
