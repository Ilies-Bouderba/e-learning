<?php

namespace App\Livewire\Admin;

use App\Traits\ManagesUsers;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManageTeachers extends Component
{
    use WithPagination, ManagesUsers;

    protected string $role = 'teacher';

    public function render()
    {
        return view('livewire.admin.manage-teachers', [
            'teachers' => $this->getUsersQuery()->withCount('courses')->latest()->paginate(10),
        ]);
    }
}
