<?php
namespace App\Livewire\Announcements;

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

        // Allow admin to view any course's announcements
        if ($user->isAdmin()) {
            $this->cour = $cour;
            return;
        }

        if ($user->isStudent()) {
            if (!$cour->enrollments()->where('student_id', $user->id)->exists()) {
                abort(403, 'You are not enrolled in this course.');
            }
        } elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403, 'You do not own this course.');
            }
        } else {
            abort(403);
        }

        $this->cour = $cour;
    }

    public function delete($id)
    {
        $user = auth()->user();

        // Only teachers who own the course or admins can delete announcements
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403);
        }

        // If teacher, verify ownership
        if ($user->isTeacher() && $this->cour->teacher_id != $user->id) {
            abort(403);
        }

        $announcement = $this->cour->announcements()->findOrFail($id);
        $announcement->delete();
        session()->flash('success', 'Announcement deleted successfully.');
    }

    public function render()
    {
        return view('livewire.announcements.index', [
            'announcements' => $this->cour->announcements()->latest('posted_at')->get(),
        ]);
    }
}
