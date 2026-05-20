<?php

namespace App\Livewire\Exams;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Course       $course;
    public Exam         $exam;
    public              $studentAttempt  = null;
    public              $studentAttempts = [];

    public function mount(Course $course, Exam $exam): mixed
    {
        if ((int) $exam->course_id !== (int) $course->id) {
            abort(404);
        }

        $user         = auth()->user();
        $this->course = $course;
        $this->exam   = $exam->load('questions');

        if ($user->isStudent()) {
            if (! $course->enrollments()->where('student_id', $user->id)->exists()) {
                abort(403);
            }

            $this->studentAttempt = ExamAttempt::where('student_id', $user->id)
                ->where('exam_id', $exam->id)
                ->first();

            if (! $this->studentAttempt || ! $this->studentAttempt->completed_at) {
                return redirect()->route('exams.take', ['course' => $course, 'exam' => $exam]);
            }

            return null;
        }

        if ($user->isTeacher()) {
            if ((int) $course->teacher_id !== (int) $user->id) {
                abort(403);
            }
            $this->studentAttempts = $this->exam->attempts()->with('student')->get();
            return null;
        }

        abort(403);
    }

    public function render()
    {
        return view('livewire.exams.show');
    }
}
