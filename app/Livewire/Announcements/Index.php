<?php

namespace App\Livewire\Announcements;

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

        if ($user->isAdmin()) {
            $this->course = $course;
            return;
        }

        if ($user->isStudent()) {
            if (! $course->enrollments()->where('student_id', $user->id)->exists()) {
                abort(403, 'You are not enrolled in this course.');
            }
        } elseif ($user->isTeacher()) {
            if ((int) $course->teacher_id !== (int) $user->id) {
                abort(403, 'You do not own this course.');
            }
        } else {
            abort(403);
        }

        $this->course = $course;
    }

    public function delete(int $id): void
    {
        $user = auth()->user();

        if (! $user->isTeacher() && ! $user->isAdmin()) {
            abort(403);
        }

        if ($user->isTeacher() && (int) $this->course->teacher_id !== (int) $user->id) {
            abort(403);
        }

        $this->course->announcements()->findOrFail($id)->delete();
        session()->flash('success', 'Announcement deleted successfully.');
    }

    public function render()
    {
        return view('livewire.announcements.index', [
            'announcements' => $this->course->announcements()->latest('posted_at')->get(),
        ]);
    }
}
