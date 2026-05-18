<?php

namespace App\Livewire\Quizzes;

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

    public function deleteQuiz(int $quizId): void
    {
        $quiz = $this->course->quizzes()->findOrFail($quizId);
        $quiz->attempts()->delete();
        $quiz->questions()->each(fn ($q) => $q->options()->delete());
        $quiz->questions()->delete();
        $quiz->delete();
        session()->flash('success', 'Quiz deleted successfully.');
    }

    public function togglePublish(int $quizId): void
    {
        $quiz               = $this->course->quizzes()->findOrFail($quizId);
        $quiz->is_published = ! $quiz->is_published;
        $quiz->save();
        session()->flash('success', 'Quiz ' . ($quiz->is_published ? 'published' : 'unpublished') . '.');
    }

    public function render()
    {
        return view('livewire.quizzes.index', [
            'quizzes' => $this->course->quizzes()->with(['questions.options', 'attempts'])->latest()->get(),
        ]);
    }
}
