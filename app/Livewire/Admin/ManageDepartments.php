<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ManageDepartments extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $name = '';

    public string $icon = '🏛️';  // FIXED: default value instead of null

    public string $description = '';

    public array $icons = ['🔬', '📐', '💻', '📖', '🎨', '🌍', '⚗️', '🧬', '🎵', '🏛️', '🧮', '📊', '🏥', '⚖️', '🌱'];

    protected array $rules = [
        'name' => 'required|string|max:255',
        'icon' => 'required|string',
        'description' => 'nullable|string|max:255'
    ];

    public function openCreate()
    {
        // FIXED: set all properties explicitly with default values
        $this->reset(['name', 'description', 'editingId']);
        $this->icon = '🏛️';  // Explicitly set default icon
        $this->showForm = true;
    }

    public function openEdit(int $id)
    {
        $d = Department::findOrFail($id);
        $this->editingId = $id;
        $this->name = $d->name;
        $this->icon = $d->icon ?? '🏛️';  // FIXED: fallback if icon is null
        $this->description = $d->description ?? '';
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        // FIXED: ensure icon is never null
        $iconValue = $this->icon ?: '🏛️';

        $data = [
            'name' => $this->name,
            'icon' => $iconValue,
            'description' => $this->description
        ];

        if ($this->editingId) {
            Department::findOrFail($this->editingId)->update($data);
        } else {
            Department::create($data);
        }

        session()->flash('success', $this->editingId ? 'Department updated.' : 'Department created.');

        // FIXED: reset with proper default values
        $this->reset(['name', 'description', 'editingId', 'showForm']);
        $this->icon = '🏛️';
    }

    public function confirmDelete(int $id)
    {
        $this->deletingId = $id;
    }

    public function cancelDelete()
    {
        $this->deletingId = null;
    }

    public function delete()
    {
        Department::findOrFail($this->deletingId)->delete();
        $this->deletingId = null;
        session()->flash('success', 'Department deleted.');
    }

    public function render()
    {
        return view('livewire.admin.manage-departments', [
            'departments' => Department::withCount('courses')->latest()->get()
        ]);
    }
}
