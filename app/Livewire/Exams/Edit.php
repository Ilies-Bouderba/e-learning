<?php

namespace App\Livewire\Exams;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Course $course;
    public Exam   $exam;

    public string $title            = '';
    public string $description      = '';
    public string $duration_minutes = '';
    public string $start_date       = '';
    public string $end_date         = '';
    public array  $questions        = [];
    public int    $currentQuestionIndex = 0;
    public bool   $showQuestionForm     = false;

    protected array $rules = [
        'title'            => 'required|string|max:255',
        'description'      => 'nullable|string',
        'duration_minutes' => 'nullable|integer|min:1|max:300',
        'start_date'       => 'nullable|date',
        'end_date'         => 'nullable|date|after:start_date',
    ];

    public function mount(Course $course, Exam $exam): void
    {
        $user = auth()->user();

        if (! $user->isTeacher() || (int) $course->teacher_id !== (int) $user->id) {
            abort(403);
        }

        if ((int) $exam->course_id !== (int) $course->id) {
            abort(404);
        }

        $this->course           = $course;
        $this->exam             = $exam;
        $this->title            = $exam->title;
        $this->description      = $exam->description ?? '';
        $this->duration_minutes = (string) ($exam->duration_minutes ?? '');
        $this->start_date       = $exam->start_date ? Carbon::parse($exam->start_date)->format('Y-m-d\TH:i') : '';
        $this->end_date         = $exam->end_date   ? Carbon::parse($exam->end_date)->format('Y-m-d\TH:i')   : '';

        $this->loadQuestions();
    }

    private function loadQuestions(): void
    {
        $this->questions = $this->exam->questions()->orderBy('order')->get()
            ->map(fn ($q) => [
                'id'            => $q->id,
                'temp_id'       => Str::random(10),
                'question_text' => $q->question_text,
                'points'        => $q->points,
            ])
            ->toArray();
    }

    public function addQuestion(): void
    {
        $this->questions[] = ['id' => null, 'temp_id' => Str::random(10), 'question_text' => '', 'points' => 10];
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
        $q = $this->questions[$index];

        if (! empty($q['id'])) {
            ExamQuestion::destroy($q['id']);
        }

        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
        session()->flash('success', 'Question removed.');
    }

    public function cancelQuestionForm(): void
    {
        $this->showQuestionForm     = false;
        $this->currentQuestionIndex = 0;
    }

    public function saveExam(): mixed
    {
        $this->validate();

        $this->exam->update([
            'title'            => $this->title,
            'description'      => $this->description,
            'duration_minutes' => $this->duration_minutes ?: null,
            'start_date'       => $this->start_date ? Carbon::parse($this->start_date)->utc() : null,
            'end_date'         => $this->end_date   ? Carbon::parse($this->end_date)->utc()   : null,
        ]);

        $keptIds = collect($this->questions)->filter(fn ($q) => ! empty($q['id']))->pluck('id')->toArray();
        $this->exam->questions()->whereNotIn('id', $keptIds)->delete();

        foreach ($this->questions as $qData) {
            if (! empty($qData['id'])) {
                ExamQuestion::where('id', $qData['id'])->update([
                    'question_text' => $qData['question_text'],
                    'points'        => $qData['points'],
                ]);
            } else {
                $this->exam->questions()->create([
                    'question_text' => $qData['question_text'],
                    'points'        => $qData['points'],
                    'order'         => 0,
                ]);
            }
        }

        $this->exam->recalculateTotalPoints();

        session()->flash('success', 'Exam updated successfully!');
        return redirect()->route('teacher.exams.index', $this->course);
    }

    public function render()
    {
        return view('livewire.exams.edit');
    }
}
