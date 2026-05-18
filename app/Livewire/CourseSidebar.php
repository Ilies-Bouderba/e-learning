<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CourseSidebar extends Component
{
    public Course $course;
    public string $active = 'chapters';

    public function mount(Course $course, string $active = 'chapters'): void
    {
        $this->course = $course;
        $this->active = $active;
    }

    public function render()
    {
        return view('livewire.course-sidebar');
    }
}
