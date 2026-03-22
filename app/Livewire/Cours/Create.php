<?php

namespace App\Livewire\Cours;

use App\Models\Cour;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Create extends Component
{
    public string $title = '';
    public string $description = '';
    public string $icon = '📚';

    public array $icons = ['📚', '🔬', '📐', '💻', '🌍', '🎨', '⚗️', '📖', '🧬', '🎵', '🏛️', '🧮'];


    protected array $rules = [
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'icon'        => 'required|string',
    ];

    public function save()
    {
        $this->validate();

        Cour::create([
            'teacher_id'  => auth()->id(),
            'title'       => $this->title,
            'description' => $this->description,
            'icon'        => $this->icon,
        ]);

        session()->flash('success', 'Course created successfully.');

        return redirect()->route('cours.create');
    }

    public function render()
    {
        return view('livewire.cours.create');
    }
}
