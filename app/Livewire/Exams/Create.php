<?php

namespace App\Livewire\Exams;

use App\Models\Course;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public Course  $course;
    public string  $title            = '';
    public string  $description      = '';
    public string  $duration_minutes = '';
    public string  $start_date       = '';
    public string  $end_date         = '';
    public array   $questions        = [];
    public int     $currentQuestionIndex = 0;
    public bool    $showQuestionForm     = false;

    protected array $rules = [
        'title'            => 'required|string|max:255',
        'description'      => 'nullable|string',
        'duration_minutes' => 'nullable|integer|min:1|max:300',
        'start_date'       => 'nullable|date',
        'end_date'         => 'nullable|date|after:start_date',
    ];

    public function mount(Course $course): void
    {
        $user = auth()->user();

        if (! $user->isTeacher() || (int) $course->teacher_id !== (int) $user->id) {
            abort(403);
        }

        $this->course = $course;
    }

    public function addQuestion(): void
    {
        $this->questions[] = ['id' => Str::random(10), 'question_text' => '', 'points' => 10];
        $this->currentQuestionIndex = count($this->questions) - 1;
        $this->showQuestionForm     = true;
    }

    public function editQuestion(int $index): void
    {
        $this->currentQuestionIndex = $index;
        $this->showQuestionForm     = true;
    }

    public function removeQuestion(int $index): void
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function cancelQuestionForm(): void
    {
        $this->showQuestionForm     = false;
        $this->currentQuestionIndex = 0;
    }

    public function saveExam(): mixed
    {
        $this->validate();

        $exam = Exam::create([
            'course_id'        => $this->course->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'duration_minutes' => $this->duration_minutes ?: null,
            'start_date'       => $this->start_date ? Carbon::parse($this->start_date)->utc() : null,
            'end_date'         => $this->end_date   ? Carbon::parse($this->end_date)->utc()   : null,
            'is_published'     => true,
        ]);

        foreach ($this->questions as $qData) {
            $exam->questions()->create([
                'question_text' => $qData['question_text'],
                'points'        => $qData['points'],
                'order'         => 0,
            ]);
        }

        $exam->recalculateTotalPoints();

        session()->flash('success', 'Exam created successfully!');
        return redirect()->route('teacher.exams.index', $this->course);
    }

    public function render()
    {
        return view('livewire.exams.create');
    }
}
