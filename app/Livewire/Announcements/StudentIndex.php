<?php

namespace App\Livewire\Announcements;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    public function render()
    {
        $enrolledCourses = auth()->user()->enrolledCourses;
        $announcements = collect();
        foreach ($enrolledCourses as $course) {
            $announcements = $announcements->merge($course->announcements);
        }
        $announcements = $announcements->sortByDesc('posted_at');

        return view('livewire.announcements.student-index', [
            'announcements' => $announcements,
        ]);
    }
}
