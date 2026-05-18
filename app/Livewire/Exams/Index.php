<?php

namespace App\Livewire\Exams;

use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public Course $course;

    public function mount(Course $course): void
    {
        $user = auth()->user();

        if (! $user->isTeacher() || (int) $course->teacher_id !== (int) $user->id) {
            abort(403);
        }

        $this->course = $course;
    }

    public function deleteExam(int $examId): void
    {
        $exam = $this->course->exams()->findOrFail($examId);
        $exam->attempts()->delete();
        $exam->questions()->delete();
        $exam->delete();
        session()->flash('success', 'Exam deleted successfully.');
    }

    public function togglePublish(int $examId): void
    {
        $exam               = $this->course->exams()->findOrFail($examId);
        $exam->is_published = ! $exam->is_published;
        $exam->save();
        session()->flash('success', 'Exam ' . ($exam->is_published ? 'published' : 'unpublished') . '.');
    }

    public function render()
    {
        return view('livewire.exams.index', [
            'exams' => $this->course->exams()->with('questions')->latest()->get(),
        ]);
    }
}
