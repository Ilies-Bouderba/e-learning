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
                return redirect()->route('student.cours.enroll', $cour);
            }
        } elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403, 'You do not own this course.');
            }
        }
        $this->cour = $cour;
    }

    public function toggleChapter($chapterId)
    {
        if (!auth()->user()->isStudent()) {
            return;
        }

        $chapter = $this->cour->chapters()->findOrFail($chapterId);
        $existingProgress = StudentProgress::where('student_id', auth()->id())
            ->where('chapter_id', $chapterId)
            ->first();

        if ($existingProgress) {
            $existingProgress->delete();
        } else {
            StudentProgress::create([
                'student_id' => auth()->id(),
                'chapter_id' => $chapterId,
                'completed' => true,
                'completed_at' => now(),
            ]);
        }

        $totalChapters = $this->cour->chapters()->count();
        $completedChapters = StudentProgress::where('student_id', auth()->id())
            ->whereIn('chapter_id', $this->cour->chapters()->pluck('id'))
            ->where('completed', true)
            ->count();

        $progress = $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0;

        $enrollment = $this->cour->enrollments()->where('student_id', auth()->id())->first();
        if ($enrollment) {
            $enrollment->update(['progress_percentage' => $progress]);
        }

        $this->cour = $this->cour->fresh();
        $this->dispatch('progress-updated');
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
