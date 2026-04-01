<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManageStudents extends Component
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
        $s = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $s->name;
        $this->email = $s->email;
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
        $data = ['name' => $this->name, 'email' => $this->email, 'role' => 'student'];
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }
        $this->editingId ? User::findOrFail($this->editingId)->update($data) : User::create($data);
        session()->flash('success', $this->editingId ? 'Student updated.' : 'Student created.');
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
        User::where('id', $this->deletingId)->where('role', 'student')->firstOrFail()->delete();
        $this->deletingId = null;
        session()->flash('success', 'Student deleted.');
    }

    public function render()
    {
        return view('livewire.admin.manage-students', ['students' => User::where('role', 'student')->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))->withCount('enrollments')->latest()->paginate(10)]);
    }
}
