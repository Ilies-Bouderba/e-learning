<?php

namespace App\Livewire\Chapters;

use App\Models\Chapter;
use App\Models\Cour;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Cour $cour;

    public Chapter $chapter;

    public function mount(Cour $cour, Chapter $chapter)
    {
        $user = auth()->user();

        // Check if the chapter belongs to the course
        if ($chapter->course_id != $cour->id) {
            abort(404, 'Chapter not found in this course.');
        }

        // Allow admin to view any chapter
        if ($user->isAdmin()) {
            $this->cour = $cour;
            $this->chapter = $chapter;
            return;
        }

        // For students: check if they're enrolled
        if ($user->isStudent()) {
            if (! $cour->enrollments()->where('student_id', $user->id)->exists()) {
                return redirect()->route('student.cours.enroll', $cour);
            }
        }
        elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403, 'You do not own this course.');
            }
        }

        $this->cour = $cour;
        $this->chapter = $chapter;
    }

    public function render()
    {
        return view('livewire.chapters.show', [
            'cour' => $this->cour,
            'chapter' => $this->chapter->load('attachments'),
        ]);
    }
}
