<?php

namespace App\Livewire\Cours;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\StudentProgress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Course $course;

    public function mount(Course $course): mixed
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            if (! $course->enrollments()->where('student_id', $user->id)->exists()) {
                return redirect()->route('student.cours.enroll', $course);
            }
        } elseif ($user->isTeacher()) {
            if ((int) $course->teacher_id !== (int) $user->id) {
                abort(403, 'You do not own this course.');
            }
        }

        $this->course = $course;
        return null;
    }

    public function toggleChapter(int $chapterId): void
    {
        if (! auth()->user()->isStudent()) {
            return;
        }

        $this->course->chapters()->findOrFail($chapterId);

        $progress = StudentProgress::where('student_id', auth()->id())
            ->where('chapter_id', $chapterId)
            ->first();

        $progress ? $progress->delete()
                  : StudentProgress::create([
                        'student_id'   => auth()->id(),
                        'chapter_id'   => $chapterId,
                        'completed'    => true,
                        'completed_at' => now(),
                    ]);

        $this->updateEnrollmentProgress();
        $this->course = $this->course->fresh();
        $this->dispatch('progress-updated');
    }

    public function deleteChapter(int $chapterId): mixed
    {
        if (! auth()->user()->isTeacher() || (int) $this->course->teacher_id !== (int) auth()->id()) {
            abort(403);
        }

        $chapter = Chapter::where('id', $chapterId)
            ->where('course_id', $this->course->id)
            ->firstOrFail();

        $chapter->attachments()->delete();
        StudentProgress::where('chapter_id', $chapter->id)->delete();
        $chapter->delete();

        session()->flash('success', 'Chapter deleted successfully.');
        return redirect()->route('cours.show', $this->course);
    }

    private function updateEnrollmentProgress(): void
    {
        $total     = $this->course->chapters()->count();
        $completed = StudentProgress::where('student_id', auth()->id())
            ->whereIn('chapter_id', $this->course->chapters()->pluck('id'))
            ->where('completed', true)
            ->count();

        $percentage = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        $this->course->enrollments()
            ->where('student_id', auth()->id())
            ->update(['progress_percentage' => $percentage]);
    }

    public function render()
    {
        return view('livewire.cours.show', [
            'course' => $this->course->load(['teacher', 'department', 'chapters', 'announcements', 'exams']),
        ]);
    }
}
