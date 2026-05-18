<?php

namespace App\Livewire\Admin;

use App\Traits\ManagesUsers;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManageStudents extends Component
{
    use WithPagination, ManagesUsers;

    protected string $role = 'student';

    public function render()
    {
        return view('livewire.admin.manage-students', [
            'students' => $this->getUsersQuery()->withCount('enrollments')->latest()->paginate(10),
        ]);
    }
}
