<?php

namespace App\Livewire\Cours;

use App\Models\Cour;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StudentCourses extends Component
{
    use WithPagination;

    public string $search = '';
    public string $department = '';

    public function mount()
    {
        if (!auth()->user()->isStudent()) {
            abort(403);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartment()
    {
        $this->resetPage();
    }

    public function render()
    {
        $studentId = auth()->id();

        // Get enrolled courses with progress
        $enrolledCourses = Cour::whereHas('enrollments', function($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->with(['teacher', 'department', 'chapters', 'exams', 'enrollments' => function($query) use ($studentId) {
            $query->where('student_id', $studentId);
        }])
        ->when($this->search, fn($q) => $q->where('title', 'like', '%'.$this->search.'%'))
        ->when($this->department, fn($q) => $q->where('department_id', $this->department))
        ->latest()
        ->get();

        // Get available courses (not enrolled and published)
        $availableCourses = Cour::whereDoesntHave('enrollments', function($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->with(['teacher', 'department', 'chapters', 'exams'])
        ->when($this->search, fn($q) => $q->where('title', 'like', '%'.$this->search.'%'))
        ->when($this->department, fn($q) => $q->where('department_id', $this->department))
        ->latest()
        ->paginate(12);

        return view('livewire.cours.student-courses', [
            'enrolledCourses' => $enrolledCourses,
            'availableCourses' => $availableCourses,
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
