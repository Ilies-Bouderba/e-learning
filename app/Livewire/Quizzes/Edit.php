<?php

namespace App\Livewire\Quizzes;

use App\Models\Cour;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Cour $cour;
    public Quiz $quiz;
    public $title = '';
    public $description = '';

    public $questions = [];
    public $currentQuestionIndex = 0;
    public $showQuestionForm = false;

    protected array $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount(Cour $cour, Quiz $quiz)
    {
        $user = auth()->user();

        if (!$user->isTeacher()) {
            abort(403, 'Only teachers can edit quizzes.');
        }

        if ($cour->teacher_id != $user->id) {
            abort(403, 'You do not own this course.');
        }

        if ($quiz->course_id != $cour->id) {
            abort(404);
        }

        $this->cour = $cour;
        $this->quiz = $quiz;
        $this->title = $quiz->title;
        $this->description = $quiz->description;

        $this->loadQuestions();
    }

    public function loadQuestions()
    {
        $dbQuestions = $this->quiz->questions()->with('options')->orderBy('order')->get();

        foreach ($dbQuestions as $dbQuestion) {
            $options = [];
            foreach ($dbQuestion->options as $dbOption) {
                $options[] = [
                    'id' => $dbOption->id,
                    'option_text' => $dbOption->option_text,
                    'is_correct' => $dbOption->is_correct,
                ];
            }

            $this->questions[] = [
                'id' => $dbQuestion->id,
                'temp_id' => Str::random(10),
                'question_text' => $dbQuestion->question_text,
                'points' => $dbQuestion->points,
                'options' => $options,
            ];
        }
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'id' => null,
            'temp_id' => Str::random(10),
            'question_text' => '',
            'points' => 1,
            'options' => [
                ['id' => null, 'temp_id' => Str::random(10), 'option_text' => '', 'is_correct' => false],
                ['id' => null, 'temp_id' => Str::random(10), 'option_text' => '', 'is_correct' => false]
            ]
        ];
        $this->currentQuestionIndex = count($this->questions) - 1;
        $this->showQuestionForm = true;
    }

    public function editQuestion($index)
    {
        $this->currentQuestionIndex = $index;
        $this->showQuestionForm = true;
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = [
            'id' => null,
            'temp_id' => Str::random(10),
            'option_text' => '',
            'is_correct' => false
        ];
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        $option = $this->questions[$questionIndex]['options'][$optionIndex];

        if (isset($option['id']) && $option['id']) {
            QuizOption::where('id', $option['id'])->delete();
        }

        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function removeQuestion($index)
    {
        $question = $this->questions[$index];

        if (isset($question['id']) && $question['id']) {
            QuizQuestion::where('id', $question['id'])->delete();
        }

        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
        session()->flash('success', 'Question removed.');
    }

    public function saveQuiz()
    {
        $this->validate();

        $this->quiz->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        foreach ($this->questions as $questionData) {
            if (isset($questionData['id']) && $questionData['id']) {
                $question = QuizQuestion::find($questionData['id']);
                if ($question) {
                    $question->update([
                        'question_text' => $questionData['question_text'],
                        'points' => $questionData['points'],
                    ]);

                    $existingOptionIds = [];
                    foreach ($questionData['options'] as $optionData) {
                        if (isset($optionData['id']) && $optionData['id']) {
                            $option = QuizOption::find($optionData['id']);
                            if ($option) {
                                $option->update([
                                    'option_text' => $optionData['option_text'],
                                    'is_correct' => $optionData['is_correct'],
                                ]);
                                $existingOptionIds[] = $option->id;
                            }
                        } else {
                            $newOption = $question->options()->create([
                                'option_text' => $optionData['option_text'],
                                'is_correct' => $optionData['is_correct'],
                            ]);
                            $existingOptionIds[] = $newOption->id;
                        }
                    }

                    $question->options()->whereNotIn('id', $existingOptionIds)->delete();
                }
            } else {
                $question = $this->quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'points' => $questionData['points'],
                    'order' => 0,
                ]);

                foreach ($questionData['options'] as $optionData) {
                    $question->options()->create([
                        'option_text' => $optionData['option_text'],
                        'is_correct' => $optionData['is_correct'],
                    ]);
                }
            }
        }

        session()->flash('success', 'Quiz updated successfully!');

        return redirect()->route('teacher.quizzes.index', $this->cour);
    }

    public function cancelQuestionForm()
    {
        $this->showQuestionForm = false;
        $this->currentQuestionIndex = null;
    }

    public function render()
    {
        return view('livewire.quizzes.edit');
    }
}
