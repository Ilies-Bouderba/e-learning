<?php

namespace App\Livewire\Announcements;

use App\Models\Announcement;
use App\Models\Cour;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public Cour $cour;

    public string $title = '';

    public string $content = '';

    protected array $rules = ['title' => 'required|string|max:255', 'content' => 'required|string'];

    public function mount(Cour $cour)
    {
        if (! auth()->user()->isTeacher()) {
            abort(403, 'Only teachers can create announcements.');
        }

        if ($cour->teacher_id != auth()->id()) {
            abort(403, 'You do not own this course.');
        }

        $this->cour = $cour;
    }

    public function save()
    {
        $this->validate();
        Announcement::create([
            'course_id' => $this->cour->id,
            'title' => $this->title,
            'content' => $this->content,
            'posted_at' => now(),
        ]);
        session()->flash('success', 'Announcement posted.');

        return redirect()->route('cours.show', $this->cour);
    }

    public function render()
    {
        return view('livewire.announcements.create');
    }
}
