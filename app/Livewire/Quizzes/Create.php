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
class Create extends Component
{
    public Cour $cour;
    public $title = '';
    public $description = '';

    public $questions = [];
    public $currentQuestionIndex = 0;
    public $showQuestionForm = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount(Cour $cour)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'You are not logged in.');
        }

        if (!$user->isTeacher()) {
            abort(403, 'Only teachers can create quizzes. Your role: ' . $user->role);
        }

        if ($cour->teacher_id != $user->id) {
            abort(403, 'You do not own this course. Course teacher: ' . $cour->teacher_id . ', You: ' . $user->id);
        }

        $this->cour = $cour;
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'id' => Str::random(10),
            'question_text' => '',
            'points' => 1,
            'options' => [
                ['id' => Str::random(10), 'option_text' => '', 'is_correct' => false],
                ['id' => Str::random(10), 'option_text' => '', 'is_correct' => false]
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
            'id' => Str::random(10),
            'option_text' => '',
            'is_correct' => false
        ];
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function saveQuiz()
    {
        $this->validate();

        foreach ($this->questions as $questionData) {
            $hasCorrect = false;
            foreach ($questionData['options'] as $option) {
                if ($option['is_correct']) {
                    $hasCorrect = true;
                    break;
                }
            }
            if (!$hasCorrect) {
                session()->flash('error', 'Each question must have at least one correct answer.');
                return;
            }
        }

        $quiz = Quiz::create([
            'course_id' => $this->cour->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_published' => true,
        ]);

        foreach ($this->questions as $questionData) {
            $question = $quiz->questions()->create([
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

        session()->flash('success', 'Quiz created successfully!');

        return redirect()->route('teacher.quizzes.index', $this->cour);
    }

    public function cancelQuestionForm()
    {
        $this->showQuestionForm = false;
        $this->currentQuestionIndex = null;
    }

    public function render()
    {
        return view('livewire.quizzes.create');
    }
}
