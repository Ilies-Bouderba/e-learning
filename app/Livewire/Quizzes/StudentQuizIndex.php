<?php

namespace App\Livewire\Quizzes;

use App\Models\Cour;
use App\Models\QuizAttempt;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentQuizIndex extends Component
{
    public Cour $cour;
    public $quizzes = [];
    public $attempts = [];

    public function mount(Cour $cour)
    {
        $user = auth()->user();

        // Allow admin to view quizzes (read-only)
        if ($user->isAdmin()) {
            $this->cour = $cour;
            $this->loadQuizzes();
            return;
        }

        if (!$user->isStudent()) {
            abort(403);
        }

        if (!$cour->enrollments()->where('student_id', $user->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $this->cour = $cour;
        $this->loadQuizzes();
    }

    public function loadQuizzes()
    {
        $this->quizzes = $this->cour->quizzes()
            ->where('is_published', true)
            ->with(['questions'])
            ->latest()
            ->get();

        $this->attempts = QuizAttempt::where('student_id', auth()->id())
            ->whereIn('quiz_id', $this->quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');
    }

    public function getQuizStatus($quizId)
    {
        $attempt = $this->attempts->get($quizId);

        if (!$attempt) {
            return 'not_started';
        }

        if ($attempt->completed_at) {
            return 'completed';
        }

        return 'in_progress';
    }

    public function render()
    {
        return view('livewire.quizzes.student-index');
    }
}
