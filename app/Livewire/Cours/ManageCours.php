<?php
// app/Livewire/Cours/ManageCours.php
namespace App\Livewire\Cours;

use App\Models\Cour;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class ManageCours extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $department    = '';
    public ?int   $deletingId    = null;

    public function mount()
    {
        $this->authorize('teacher');
    }

    public function updatingSearch()     { $this->resetPage(); }
    public function updatingDepartment() { $this->resetPage(); }

    public function confirmDelete(int $id) { $this->deletingId = $id; }
    public function cancelDelete()         { $this->deletingId = null; }

    public function delete()
    {
        Cour::where('id', $this->deletingId)
            ->where('teacher_id', auth()->id())
            ->firstOrFail()
            ->delete();

        $this->deletingId = null;
        session()->flash('success', 'Course deleted.');
    }

    public function render()
    {
        $courses = auth()->user()->courses()
            ->with('department')
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->department, fn($q) => $q->where('department_id', $this->department))
            ->latest()
            ->paginate(8);

        return view('livewire.cours.index', [
            'courses'     => $courses,
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
