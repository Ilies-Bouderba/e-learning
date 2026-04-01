<?php

namespace App\Livewire\Announcements;

use App\Models\Cour;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public Cour $cour;

    public function mount(Cour $cour)
    {
        if (! auth()->user()->isTeacher()) {
            abort(403, 'Only teachers can manage announcements.');
        }

        if ($cour->teacher_id != auth()->id()) {
            abort(403, 'You do not own this course.');
        }

        $this->cour = $cour;
    }

    public function delete($id)
    {
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
