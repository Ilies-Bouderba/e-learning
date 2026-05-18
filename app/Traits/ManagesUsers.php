<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Shared CRUD logic for admin user-management components.
 * The consuming class must define a $role property ('student' | 'teacher').
 */
trait ManagesUsers
{
    public string $search = '';
    public bool $showForm = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';

    protected array $userRules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email',
        'password' => 'nullable|string|min:6',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'email', 'password', 'editingId']);
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->password  = '';
        $this->showForm  = true;
    }

    public function save(): void
    {
        $rules = $this->userRules;

        if (! $this->editingId) {
            $rules['password'] = 'required|string|min:6';
        }

        $rules['email'] .= '|unique:users,email' . ($this->editingId ? ',' . $this->editingId : '');

        $this->validate($rules);

        $data = ['name' => $this->name, 'email' => $this->email, 'role' => $this->role];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $this->editingId
            ? User::findOrFail($this->editingId)->update($data)
            : User::create($data);

        session()->flash('success', $this->editingId ? ucfirst($this->role) . ' updated.' : ucfirst($this->role) . ' created.');
        $this->reset(['name', 'email', 'password', 'editingId', 'showForm']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        User::where('id', $this->deletingId)->where('role', $this->role)->firstOrFail()->delete();
        $this->deletingId = null;
        session()->flash('success', ucfirst($this->role) . ' deleted.');
    }

    protected function getUsersQuery()
    {
        return User::where('role', $this->role)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"));
    }
}
