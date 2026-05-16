<?php

namespace App\Livewire\Exams;

use App\Models\Cour;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    public Cour $cour;
    public $exams = [];

    public function mount(Cour $cour)
    {
        $user = auth()->user();

        // Allow both students and teachers to view exams
        if ($user->isStudent()) {
            if (!$cour->enrollments()->where('student_id', $user->id)->exists()) {
                return redirect()->route('student.cours.enroll', $cour);
            }
            $this->exams = $cour->exams()
                ->where('is_published', true)
                ->with('questions')
                ->latest()
                ->get();
        } elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403);
            }
            $this->exams = $cour->exams()
                ->with('questions')
                ->latest()
                ->get();
        } else {
            abort(403);
        }

        $this->cour = $cour;
    }

    public function render()
    {
        return view('livewire.exams.student-index');
    }
}
