<?php

namespace App\Livewire\Quizzes;

use App\Models\Course;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    public Course $course;
    public        $quizzes  = [];
    public        $attempts = [];

    public function mount(Course $course): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $this->course = $course;
            $this->loadQuizzes();
            return;
        }

        if (! $user->isStudent()) {
            abort(403);
        }

        if (! $course->enrollments()->where('student_id', $user->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $this->course = $course;
        $this->loadQuizzes();
    }

    private function loadQuizzes(): void
    {
        $this->quizzes = $this->course->quizzes()
            ->where('is_published', true)
            ->with('questions')
            ->latest()
            ->get();

        $this->attempts = QuizAttempt::where('student_id', auth()->id())
            ->whereIn('quiz_id', $this->quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');
    }

    public function getQuizStatus(int $quizId): string
    {
        $attempt = $this->attempts->get($quizId);

        if (! $attempt)             return 'not_started';
        if ($attempt->completed_at) return 'completed';
        return 'in_progress';
    }

    public function render()
    {
        return view('livewire.quizzes.student-index');
    }
}
