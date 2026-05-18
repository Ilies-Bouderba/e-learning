<?php

namespace App\Livewire\Cours;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Course $course;

    public string $title        = '';
    public string $description  = '';
    public string $icon         = '📚';
    public ?int   $department_id = null;
    public string $password     = '';
    public bool   $has_password  = false;

    public array $icons = ['📚', '🔬', '📐', '💻', '🌍', '🎨', '⚗️', '📖', '🧬', '🎵', '🏛️', '🧮'];

    protected array $rules = [
        'title'         => 'required|string|max:255',
        'description'   => 'nullable|string|max:1000',
        'icon'          => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'password'      => 'nullable|string|min:4',
    ];

    public function mount(Course $course): void
    {
        $user = auth()->user();

        if (! $user->isTeacher()) {
            abort(403, 'Only teachers can edit courses.');
        }

        if ((int) $course->teacher_id !== (int) $user->id) {
            abort(403, 'You do not own this course.');
        }

        $this->course        = $course;
        $this->title         = $course->title;
        $this->description   = $course->description ?? '';
        $this->icon          = $course->icon;
        $this->department_id = $course->department_id;
        $this->has_password  = $course->hasPassword();
    }

    public function save(): mixed
    {
        $this->validate();

        $this->course->update([
            'department_id' => $this->department_id,
            'icon'          => $this->icon,
            'title'         => $this->title,
            'description'   => $this->description,
            'password'      => $this->has_password && $this->password
                                ? Hash::make($this->password)
                                : ($this->has_password ? $this->course->getRawOriginal('password') : null),
        ]);

        session()->flash('success', 'Course updated successfully.');
        return redirect()->route('cours.index');
    }

    public function render()
    {
        return view('livewire.cours.edit', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
