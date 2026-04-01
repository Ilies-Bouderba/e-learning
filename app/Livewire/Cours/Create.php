<?php

namespace App\Livewire\Cours;

use App\Models\Cour;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public string $title = '';

    public string $description = '';

    public string $icon = '📚';

    public ?int $department_id = null;

    public string $password = '';

    public bool $has_password = false;

    public array $icons = ['📚', '🔬', '📐', '💻', '🌍', '🎨', '⚗️', '📖', '🧬', '🎵', '🏛️', '🧮'];

    protected array $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'icon' => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'password' => 'nullable|string|min:4',
    ];

    public function mount()
    {
        if (! auth()->user()->isTeacher()) {
            abort(403, 'Only teachers can create courses.');
        }
    }

    public function save()
    {
        $this->validate();
        Cour::create([
            'teacher_id' => auth()->id(),
            'department_id' => $this->department_id,
            'icon' => $this->icon,
            'title' => $this->title,
            'description' => $this->description,
            'password' => $this->has_password && $this->password ? Hash::make($this->password) : null,
        ]);
        session()->flash('success', 'Course created successfully.');

        return redirect()->route('cours.index');
    }

    public function render()
    {
        return view('livewire.cours.create', ['departments' => Department::orderBy('name')->get()]);
    }
}
