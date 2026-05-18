<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ManageDepartments extends Component
{
    public bool    $showForm   = false;
    public ?int    $editingId  = null;
    public ?int    $deletingId = null;
    public string  $name       = '';
    public string  $icon       = '🏛️';
    public string  $description = '';

    public array $icons = ['🔬', '📐', '💻', '📖', '🎨', '🌍', '⚗️', '🧬', '🎵', '🏛️', '🧮', '📊', '🏥', '⚖️', '🌱'];

    protected array $rules = [
        'name'        => 'required|string|max:255',
        'icon'        => 'required|string',
        'description' => 'nullable|string|max:255',
    ];

    public function openCreate(): void
    {
        $this->reset(['name', 'description', 'editingId']);
        $this->icon     = '🏛️';
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $dept             = Department::findOrFail($id);
        $this->editingId  = $id;
        $this->name       = $dept->name;
        $this->icon       = $dept->icon ?? '🏛️';
        $this->description = $dept->description ?? '';
        $this->showForm   = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'        => $this->name,
            'icon'        => $this->icon ?: '🏛️',
            'description' => $this->description,
        ];

        $this->editingId
            ? Department::findOrFail($this->editingId)->update($data)
            : Department::create($data);

        session()->flash('success', $this->editingId ? 'Department updated.' : 'Department created.');

        $this->reset(['name', 'description', 'editingId', 'showForm']);
        $this->icon = '🏛️';
    }

    public function confirmDelete(int $id): void { $this->deletingId = $id; }
    public function cancelDelete(): void         { $this->deletingId = null; }

    public function delete(): void
    {
        Department::findOrFail($this->deletingId)->delete();
        $this->deletingId = null;
        session()->flash('success', 'Department deleted.');
    }

    public function render()
    {
        return view('livewire.admin.manage-departments', [
            'departments' => Department::withCount('courses')->latest()->get(),
        ]);
    }
}
