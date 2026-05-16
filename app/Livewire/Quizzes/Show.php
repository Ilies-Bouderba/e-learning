<?php

namespace App\Livewire\Quizzes;

use App\Models\Quiz;
use App\Models\Cour;
use App\Models\QuizAttempt;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Show extends Component
{
    public Cour $cour;
    public Quiz $quiz;
    public $studentAttempt = null;
    public $studentAttempts = [];

    public function mount(Cour $cour, Quiz $quiz)
    {
        if ($quiz->course_id != $cour->id) {
            abort(404);
        }

        $user = auth()->user();

        $this->cour = $cour;
        $this->quiz = $quiz->load(['questions.options']);

        // Allow admin to view any quiz results
        if ($user->isAdmin()) {
            $this->studentAttempts = $this->quiz->attempts()->with('student')->get();
            return;
        }

        if ($user->isStudent()) {
            if (!$cour->enrollments()->where('student_id', $user->id)->exists()) {
                abort(403, 'You are not enrolled in this course.');
            }

            $this->studentAttempt = QuizAttempt::where('student_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->first();

            if (!$this->studentAttempt || !$this->studentAttempt->completed_at) {
                return redirect()->route('student.quizzes.take', ['cour' => $cour, 'quiz' => $quiz]);
            }
        } elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403);
            }
            $this->studentAttempts = $this->quiz->attempts()->with('student')->get();
        } else {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.quizzes.show');
    }
}
