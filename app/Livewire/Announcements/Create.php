<?php

namespace App\Livewire\Announcements;

use App\Models\Announcement;
use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public Course $course;
    public string $title   = '';
    public string $content = '';

    protected array $rules = [
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
    ];

    public function mount(Course $course): void
    {
        $user = auth()->user();

        if (! $user->isTeacher()) {
            abort(403, 'Only teachers can create announcements.');
        }

        if ((int) $course->teacher_id !== (int) $user->id) {
            abort(403, 'You do not own this course.');
        }

        $this->course = $course;
    }

    public function save(): mixed
    {
        $this->validate();

        Announcement::create([
            'course_id' => $this->course->id,
            'title'     => $this->title,
            'content'   => $this->content,
            'posted_at' => now(),
        ]);

        session()->flash('success', 'Announcement posted successfully.');
        return redirect()->route('cours.show', $this->course);
    }

    public function render()
    {
        return view('livewire.announcements.create');
    }
}
