<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManageTeachers extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    protected array $rules = ['name' => 'required|string|max:255', 'email' => 'required|email', 'password' => 'nullable|string|min:6'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['name', 'email', 'password', 'editingId']);
        $this->showForm = true;
    }

    public function openEdit(int $id)
    {
        $t = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $t->name;
        $this->email = $t->email;
        $this->password = '';
        $this->showForm = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if (! $this->editingId) {
            $rules['password'] = 'required|string|min:6';
        }
        $rules['email'] .= '|unique:users,email'.($this->editingId ? ','.$this->editingId : '');
        $this->validate($rules);
        $data = ['name' => $this->name, 'email' => $this->email, 'role' => 'teacher'];
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }
        $this->editingId ? User::findOrFail($this->editingId)->update($data) : User::create($data);
        session()->flash('success', $this->editingId ? 'Teacher updated.' : 'Teacher created.');
        $this->reset(['name', 'email', 'password', 'editingId', 'showForm']);
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
        User::where('id', $this->deletingId)->where('role', 'teacher')->firstOrFail()->delete();
        $this->deletingId = null;
        session()->flash('success', 'Teacher deleted.');
    }

    public function render()
    {
        return view('livewire.admin.manage-teachers', ['teachers' => User::where('role', 'teacher')->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))->withCount('courses')->latest()->paginate(10)]);
    }
}
