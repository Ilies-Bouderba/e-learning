<?php

namespace App\Livewire\Cours;

use App\Models\Cour;
use App\Models\Department;
use App\Models\StudentExam;
use App\Models\StudentProgress;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManageCours extends Component
{
    use WithPagination;

    public string $search = '';

    public string $department = '';

    public ?int $deletingId = null;

    public function mount()
    {
        if (! auth()->user()->isTeacher()) {
            abort(403, 'Only teachers can access this page.');
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
        $course = Cour::where('id', $this->deletingId)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();


        $course->enrollments()->delete();

        $course->comments()->delete();

        foreach ($course->chapters as $chapter) {
            StudentProgress::where('chapter_id', $chapter->id)->delete();

            $chapter->attachments()->delete();

            $chapter->delete();
        }

        foreach ($course->exams as $exam) {
            StudentExam::where('exam_id', $exam->id)->delete();

            $exam->questions()->delete();

            $exam->delete();
        }

        $course->announcements()->delete();

        $course->delete();

        $this->deletingId = null;
        session()->flash('success', 'Course deleted successfully.');
    }

    public function render()
    {
        return view('livewire.cours.index', [
            'courses' => auth()->user()->courses()
                ->with('department')
                ->when($this->search, fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
                ->when($this->department, fn ($q) => $q->where('department_id', $this->department))
                ->latest()->paginate(8),
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
