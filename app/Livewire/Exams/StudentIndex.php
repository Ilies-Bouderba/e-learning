<?php

namespace App\Livewire\Exams;

use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    public Course $course;
    public        $exams = [];

    public function mount(Course $course): mixed
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            if (! $course->enrollments()->where('student_id', $user->id)->exists()) {
                return redirect()->route('student.cours.enroll', $course);
            }

            $this->exams = $course->exams()
                ->where('is_published', true)
                ->with('questions')
                ->latest()
                ->get();
        } elseif ($user->isTeacher()) {
            if ((int) $course->teacher_id !== (int) $user->id) {
                abort(403);
            }

            $this->exams = $course->exams()->with('questions')->latest()->get();
        } else {
            abort(403);
        }

        $this->course = $course;
        return null;
    }

    public function render()
    {
        return view('livewire.exams.student-index');
    }
}
