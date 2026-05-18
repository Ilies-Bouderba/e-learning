<?php

namespace App\Livewire\Quizzes;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public Course  $course;
    public string  $title       = '';
    public string  $description = '';
    public array   $questions   = [];
    public int     $currentQuestionIndex = 0;
    public bool    $showQuestionForm     = false;

    protected array $rules = [
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
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
        $this->questions[] = [
            'id'            => Str::random(10),
            'question_text' => '',
            'points'        => 1,
            'options'       => [
                ['id' => Str::random(10), 'option_text' => '', 'is_correct' => false],
                ['id' => Str::random(10), 'option_text' => '', 'is_correct' => false],
            ],
        ];
        $this->currentQuestionIndex = count($this->questions) - 1;
        $this->showQuestionForm     = true;
    }

    public function editQuestion(int $index): void
    {
        $this->currentQuestionIndex = $index;
        $this->showQuestionForm     = true;
    }

    public function addOption(int $questionIndex): void
    {
        $this->questions[$questionIndex]['options'][] = [
            'id'          => Str::random(10),
            'option_text' => '',
            'is_correct'  => false,
        ];
    }

    public function removeOption(int $questionIndex, int $optionIndex): void
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
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

    public function saveQuiz(): mixed
    {
        $this->validate();

        foreach ($this->questions as $q) {
            $hasCorrect = collect($q['options'])->contains('is_correct', true);
            if (! $hasCorrect) {
                session()->flash('error', 'Each question must have at least one correct answer.');
                return null;
            }
        }

        $quiz = Quiz::create([
            'course_id'    => $this->course->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'is_published' => true,
        ]);

        foreach ($this->questions as $qData) {
            $question = $quiz->questions()->create([
                'question_text' => $qData['question_text'],
                'points'        => $qData['points'],
                'order'         => 0,
            ]);

            foreach ($qData['options'] as $oData) {
                $question->options()->create([
                    'option_text' => $oData['option_text'],
                    'is_correct'  => $oData['is_correct'],
                ]);
            }
        }

        session()->flash('success', 'Quiz created successfully!');
        return redirect()->route('teacher.quizzes.index', $this->course);
    }

    public function render()
    {
        return view('livewire.quizzes.create');
    }
}
