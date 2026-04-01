<?php

namespace App\Livewire\Cours;

use App\Models\Chapter;
use App\Models\Cour;
use App\Models\StudentProgress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Cour $cour;

    public function mount(Cour $cour)
    {
        $user = auth()->user();
        if ($user->isStudent()) {
            if (! $cour->enrollments()->where('student_id', $user->id)->exists()) {
                return redirect()->route('cours.enroll', $cour);
            }
        } elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403, 'You do not own this course.');
            }
        }
        $this->cour = $cour;
    }

    public function deleteChapter($chapterId)
    {
        $chapter = Chapter::where('id', $chapterId)
            ->where('course_id', $this->cour->id)
            ->firstOrFail();

        if (! auth()->user()->isTeacher() || $this->cour->teacher_id != auth()->id()) {
            abort(403);
        }

        $chapter->attachments()->delete();
        StudentProgress::where('chapter_id', $chapter->id)->delete();
        $chapter->delete();

        session()->flash('success', 'Chapter deleted successfully.');

        return redirect()->route('cours.show', $this->cour);
    }

    public function render()
    {
        return view('livewire.cours.show', [
            'cour' => $this->cour->load(['teacher', 'department', 'chapters', 'announcements', 'exams']),
        ]);
    }
}
