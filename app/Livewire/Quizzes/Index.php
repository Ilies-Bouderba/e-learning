<?php

namespace App\Livewire\Quizzes;

use App\Models\Cour;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    public Cour $cour;

    public function mount(Cour $cour)
    {
        $user = auth()->user();

        if (!$user->isTeacher()) {
            abort(403, 'Only teachers can access this page.');
        }

        if ($cour->teacher_id != $user->id) {
            abort(403, 'You do not own this course.');
        }

        $this->cour = $cour;
    }

    public function deleteQuiz($quizId)
    {
        $user = auth()->user();

        // Only teachers who own the course can delete
        if (!$user->isTeacher() || $this->cour->teacher_id != $user->id) {
            abort(403);
        }

        $quiz = $this->cour->quizzes()->findOrFail($quizId);
        $quiz->attempts()->delete();
        $quiz->delete();
        session()->flash('success', 'Quiz deleted successfully.');
    }

    public function togglePublish($quizId)
    {
        $user = auth()->user();

        if (!$user->isTeacher() || $this->cour->teacher_id != $user->id) {
            abort(403);
        }

        $quiz = $this->cour->quizzes()->findOrFail($quizId);
        $quiz->is_published = !$quiz->is_published;
        $quiz->save();
        session()->flash('success', 'Quiz ' . ($quiz->is_published ? 'published' : 'unpublished') . ' successfully.');
    }

    public function render()
    {
        return view('livewire.quizzes.index', [
            'quizzes' => $this->cour->quizzes()->with(['questions.options', 'attempts'])->latest()->get()
        ]);
    }
}
