<?php
namespace App\Livewire\Announcements;

use App\Models\Announcement;
use App\Models\Cour;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Create extends Component
{
    public Cour $cour;
    public string $title = '';
    public string $content = '';

    protected array $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string'
    ];

    public function mount(Cour $cour)
    {
        \Log::info('Announcement Create Mount', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'course_teacher_id' => $cour->teacher_id,
            'is_teacher' => auth()->user()->isTeacher(),
            'matches' => $cour->teacher_id == auth()->id()
        ]);

        if (!auth()->user()->isTeacher()) {
            abort(403, 'Only teachers can create announcements.');
        }

        if ($cour->teacher_id != auth()->id()) {
            abort(403, 'You do not own this course. Course teacher: ' . $cour->teacher_id . ', You: ' . auth()->id());
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
            'posted_at' => now()
        ]);

        session()->flash('success', 'Announcement posted successfully.');
        return redirect()->route('cours.show', $this->cour);
    }

    public function render()
    {
        return view('livewire.announcements.create');
    }
}
