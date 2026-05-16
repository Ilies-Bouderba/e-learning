<?php

namespace App\Livewire\Exams;

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
            abort(403);
        }

        if ($cour->teacher_id != $user->id) {
            abort(403);
        }

        $this->cour = $cour;
    }

    public function deleteExam($examId)
    {
        $exam = $this->cour->exams()->findOrFail($examId);
        $exam->attempts()->delete();
        $exam->questions()->delete();
        $exam->delete();
        session()->flash('success', 'Exam deleted successfully.');
    }

    public function togglePublish($examId)
    {
        $exam = $this->cour->exams()->findOrFail($examId);
        $exam->is_published = !$exam->is_published;
        $exam->save();
        session()->flash('success', 'Exam ' . ($exam->is_published ? 'published' : 'unpublished') . ' successfully.');
    }

    public function render()
    {
        return view('livewire.exams.index', [
            'exams' => $this->cour->exams()->with('questions')->latest()->get(),
        ]);
    }
}
