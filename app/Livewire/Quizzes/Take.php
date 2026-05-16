<?php

namespace App\Livewire\Quizzes;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Cour;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Take extends Component
{
    public Cour $cour;
    public Quiz $quiz;
    public $attempt = null;
    public $answers = [];
    public $currentQuestion = 0;
    public $questions = [];

    public function mount(Cour $cour, Quiz $quiz)
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        if (!$cour->enrollments()->where('student_id', $student->id)->exists()) {
            return redirect()->route('cours.enroll', $cour);
        }

        $this->cour = $cour;
        $this->quiz = $quiz;
        $this->questions = $quiz->questions()->with('options')->orderBy('order')->get();

        $this->attempt = QuizAttempt::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->first();

        if (!$this->attempt) {
            $this->attempt = QuizAttempt::create([
                'student_id' => $student->id,
                'quiz_id' => $quiz->id,
                'started_at' => now(),
            ]);
        }

        $this->loadAnswers();
    }

    public function loadAnswers()
    {
        if ($this->attempt->answers) {
            $this->answers = $this->attempt->answers;
        } else {
            foreach ($this->questions as $index => $question) {
                $this->answers[$question->id] = null;
            }
        }
    }

    public function saveAnswer($questionId, $answer)
    {
        $this->answers[$questionId] = $answer;
        $this->attempt->update(['answers' => $this->answers]);
    }

    public function nextQuestion()
    {
        if ($this->currentQuestion < count($this->questions) - 1) {
            $this->currentQuestion++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestion > 0) {
            $this->currentQuestion--;
        }
    }

    public function submitQuiz()
    {
        $score = 0;
        $totalPoints = 0;

        foreach ($this->questions as $question) {
            $totalPoints += $question->points;
            $userAnswer = $this->answers[$question->id] ?? null;

            if ($userAnswer) {
                $correctOption = $question->options()->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == $userAnswer) {
                    $score += $question->points;
                }
            }
        }

        $percentageScore = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;

        $this->attempt->update([
            'completed_at' => now(),
            'score' => $percentageScore,
            'is_graded' => true,
            'answers' => $this->answers
        ]);

        session()->flash('success', "Quiz submitted! Your score: " . round($percentageScore) . "%");
        return redirect()->route('cours.show', $this->cour);
    }

    public function render()
    {
        return view('livewire.quizzes.take', [
            'questions' => $this->questions,
            'currentQuestionData' => $this->questions[$this->currentQuestion] ?? null,
            'progress' => count($this->questions) > 0 ? (($this->currentQuestion + 1) / count($this->questions)) * 100 : 0
        ]);
    }
}
