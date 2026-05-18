<?php

namespace App\Livewire\Cours;

use App\Models\Course;
use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StudentCourses extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $department = '';

    public function mount(): void
    {
        if (! auth()->user()->isStudent()) {
            abort(403);
        }
    }

    public function updatingSearch(): void     { $this->resetPage(); }
    public function updatingDepartment(): void { $this->resetPage(); }

    public function render()
    {
        $studentId = auth()->id();

        $enrolledCourses = Course::whereHas('enrollments', fn ($q) => $q->where('student_id', $studentId))
            ->with(['teacher', 'department', 'chapters', 'exams',
                    'enrollments' => fn ($q) => $q->where('student_id', $studentId)])
            ->when($this->search,     fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->department, fn ($q) => $q->where('department_id', $this->department))
            ->latest()
            ->get();

        $availableCourses = Course::whereDoesntHave('enrollments', fn ($q) => $q->where('student_id', $studentId))
            ->with(['teacher', 'department', 'chapters', 'exams'])
            ->when($this->search,     fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->department, fn ($q) => $q->where('department_id', $this->department))
            ->latest()
            ->paginate(12);

        return view('livewire.cours.student-courses', [
            'enrolledCourses'  => $enrolledCourses,
            'availableCourses' => $availableCourses,
            'departments'      => Department::orderBy('name')->get(),
        ]);
    }
}
