<?php

namespace App\Livewire\Announcements;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    public function render()
    {
        $announcements = auth()->user()
            ->enrolledCourses()
            ->with('announcements')
            ->get()
            ->flatMap(fn ($course) => $course->announcements)
            ->sortByDesc('posted_at');

        return view('livewire.announcements.student-index', [
            'announcements' => $announcements,
        ]);
    }
}
