<?php

namespace App\Livewire\Cours;

use App\Models\Course;
use App\Models\Department;
use App\Models\StudentProgress;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Manage extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $department = '';
    public ?int   $deletingId = null;

    public function mount(): void
    {
        if (! auth()->user()->isTeacher()) {
            abort(403, 'Only teachers can access this page.');
        }
    }

    public function updatingSearch(): void     { $this->resetPage(); }
    public function updatingDepartment(): void { $this->resetPage(); }

    public function confirmDelete(int $id): void { $this->deletingId = $id; }
    public function cancelDelete(): void         { $this->deletingId = null; }

    public function delete(): void
    {
        $course = Course::where('id', $this->deletingId)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        // Delete child records (SQL Server cascades are limited, so we clean up manually)
        foreach ($course->chapters as $chapter) {
            StudentProgress::where('chapter_id', $chapter->id)->delete();
            $chapter->attachments()->delete();
            $chapter->comments()->delete();
            $chapter->delete();
        }

        foreach ($course->exams as $exam) {
            $exam->attempts()->delete();
            $exam->questions()->delete();
            $exam->delete();
        }

        foreach ($course->quizzes as $quiz) {
            $quiz->attempts()->delete();
            $quiz->questions()->each(function ($q) {
                $q->options()->delete();
                $q->delete();
            });
            $quiz->delete();
        }

        $course->enrollments()->delete();
        $course->announcements()->delete();
        $course->delete();

        $this->deletingId = null;
        session()->flash('success', 'Course deleted successfully.');
    }

    public function render()
    {
        return view('livewire.cours.index', [
            'courses'     => auth()->user()->courses()
                ->with('department')
                ->when($this->search,     fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
                ->when($this->department, fn ($q) => $q->where('department_id', $this->department))
                ->latest()
                ->paginate(8),
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
