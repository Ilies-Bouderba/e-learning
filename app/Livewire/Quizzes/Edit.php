<?php

namespace App\Livewire\Quizzes;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Course $course;
    public Quiz   $quiz;

    public string $title       = '';
    public string $description = '';
    public array  $questions   = [];
    public int    $currentQuestionIndex = 0;
    public bool   $showQuestionForm     = false;

    protected array $rules = [
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount(Course $course, Quiz $quiz): void
    {
        $user = auth()->user();

        if (! $user->isTeacher() || (int) $course->teacher_id !== (int) $user->id) {
            abort(403);
        }

        if ($quiz->course_id !== $course->id) {
            abort(404);
        }

        $this->course      = $course;
        $this->quiz        = $quiz;
        $this->title       = $quiz->title;
        $this->description = $quiz->description ?? '';

        $this->loadQuestions();
    }

    private function loadQuestions(): void
    {
        $this->questions = $this->quiz->questions()->with('options')->orderBy('order')->get()
            ->map(fn ($q) => [
                'id'            => $q->id,
                'temp_id'       => Str::random(10),
                'question_text' => $q->question_text,
                'points'        => $q->points,
                'options'       => $q->options->map(fn ($o) => [
                    'id'          => $o->id,
                    'option_text' => $o->option_text,
                    'is_correct'  => $o->is_correct,
                ])->toArray(),
            ])
            ->toArray();
    }

    public function addQuestion(): void
    {
        $this->questions[] = [
            'id'            => null,
            'temp_id'       => Str::random(10),
            'question_text' => '',
            'points'        => 1,
            'options'       => [
                ['id' => null, 'temp_id' => Str::random(10), 'option_text' => '', 'is_correct' => false],
                ['id' => null, 'temp_id' => Str::random(10), 'option_text' => '', 'is_correct' => false],
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
            'id' => null, 'temp_id' => Str::random(10), 'option_text' => '', 'is_correct' => false,
        ];
    }

    public function removeOption(int $questionIndex, int $optionIndex): void
    {
        $option = $this->questions[$questionIndex]['options'][$optionIndex];

        if (! empty($option['id'])) {
            QuizOption::destroy($option['id']);
        }

        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function removeQuestion(int $index): void
    {
        $question = $this->questions[$index];

        if (! empty($question['id'])) {
            QuizQuestion::destroy($question['id']);
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

    public function saveQuiz(): mixed
    {
        $this->validate();

        $this->quiz->update([
            'title'       => $this->title,
            'description' => $this->description,
        ]);

        foreach ($this->questions as $qData) {
            if (! empty($qData['id'])) {
                $question = QuizQuestion::find($qData['id']);

                if ($question) {
                    $question->update([
                        'question_text' => $qData['question_text'],
                        'points'        => $qData['points'],
                    ]);

                    $keptOptionIds = [];

                    foreach ($qData['options'] as $oData) {
                        if (! empty($oData['id'])) {
                            QuizOption::where('id', $oData['id'])->update([
                                'option_text' => $oData['option_text'],
                                'is_correct'  => $oData['is_correct'],
                            ]);
                            $keptOptionIds[] = $oData['id'];
                        } else {
                            $newOpt          = $question->options()->create([
                                'option_text' => $oData['option_text'],
                                'is_correct'  => $oData['is_correct'],
                            ]);
                            $keptOptionIds[] = $newOpt->id;
                        }
                    }

                    $question->options()->whereNotIn('id', $keptOptionIds)->delete();
                }
            } else {
                $question = $this->quiz->questions()->create([
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
        }

        session()->flash('success', 'Quiz updated successfully!');
        return redirect()->route('teacher.quizzes.index', $this->course);
    }

    public function render()
    {
        return view('livewire.quizzes.edit');
    }
}
