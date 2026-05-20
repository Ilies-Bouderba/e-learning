<?php

namespace App\Livewire\Quizzes;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Course        $course;
    public Quiz          $quiz;
    public               $studentAttempt  = null;
    public               $studentAttempts = [];

    public function mount(Course $course, Quiz $quiz): mixed
    {
        if ((int) $quiz->course_id !== (int) $course->id) {
            abort(404);
        }

        $user         = auth()->user();
        $this->course = $course;
        $this->quiz   = $quiz->load(['questions.options']);

        if ($user->isAdmin()) {
            $this->studentAttempts = $this->quiz->attempts()->with('student')->get();
            return null;
        }

        if ($user->isStudent()) {
            if (! $course->enrollments()->where('student_id', $user->id)->exists()) {
                abort(403, 'You are not enrolled in this course.');
            }

            $this->studentAttempt = QuizAttempt::where('student_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->first();

            if (! $this->studentAttempt || ! $this->studentAttempt->completed_at) {
                return redirect()->route('student.quizzes.take', ['course' => $course, 'quiz' => $quiz]);
            }

            return null;
        }

        if ($user->isTeacher()) {
            if ((int) $course->teacher_id !== (int) $user->id) {
                abort(403);
            }
            $this->studentAttempts = $this->quiz->attempts()->with('student')->get()->toArray();
            return null;
        }

        abort(403);
    }

    public function render()
    {
        return view('livewire.quizzes.show');
    }
}
