<?php

namespace App\Livewire\Exams;

use App\Models\Cour;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Show extends Component
{
    public Cour $cour;
    public Exam $exam;
    public $studentAttempt = null;
    public $studentAttempts = [];

    public function mount(Cour $cour, Exam $exam)
    {
        if ($exam->course_id != $cour->id) {
            abort(404);
        }

        $user = auth()->user();

        $this->cour = $cour;
        $this->exam = $exam->load(['questions']);

        if ($user->isStudent()) {
            if (!$cour->enrollments()->where('student_id', $user->id)->exists()) {
                abort(403);
            }

            $this->studentAttempt = ExamAttempt::where('student_id', $user->id)
                ->where('exam_id', $exam->id)
                ->first();

            if (!$this->studentAttempt || !$this->studentAttempt->completed_at) {
                return redirect()->route('exams.take', ['cour' => $cour, 'exam' => $exam]);
            }
        } elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403);
            }
            $this->studentAttempts = $this->exam->attempts()->with('student')->get();
        } else {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.exams.show');
    }
}
