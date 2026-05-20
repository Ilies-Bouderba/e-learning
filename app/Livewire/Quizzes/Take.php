<?php

namespace App\Livewire\Quizzes;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Take extends Component
{
    public Course      $course;
    public Quiz        $quiz;
    public             $attempt         = null;
    public array       $answers         = [];
    public int         $currentQuestion = 0;
    public             $questions       = [];

    public function mount(Course $course, Quiz $quiz): mixed
    {
        $student = auth()->user();

        if (! $student->isStudent()) {
            abort(403);
        }

        if (! $course->enrollments()->where('student_id', $student->id)->exists()) {
            return redirect()->route('student.cours.enroll', $course);
        }

        $this->course    = $course;
        $this->quiz      = $quiz;
        $this->questions = $quiz->questions()->with('options')->orderBy('order')->get();

        $this->attempt = QuizAttempt::firstOrCreate(
            ['student_id' => $student->id, 'quiz_id' => $quiz->id],
            ['started_at' => now()]
        );

        $this->loadAnswers();
        return null;
    }

    private function loadAnswers(): void
    {
        if ($this->attempt->answers) {
            $this->answers = $this->attempt->answers;
            return;
        }

        $this->answers = $this->questions->mapWithKeys(fn ($q) => [$q->id => null])->toArray();
    }

    public function saveAnswer(int $questionId, mixed $answer): void
    {
        $this->answers[$questionId] = $answer;
        $this->attempt->update(['answers' => $this->answers]);
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestion < count($this->questions) - 1) {
            $this->currentQuestion++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestion > 0) {
            $this->currentQuestion--;
        }
    }

    public function submitQuiz(): mixed
    {
        $score       = 0;
        $totalPoints = 0;

        foreach ($this->questions as $question) {
            $totalPoints  += $question->points;
            $userAnswer    = $this->answers[$question->id] ?? null;
            $correctOption = $question->options()->where('is_correct', true)->first();

            if ($userAnswer && $correctOption && $correctOption->id == $userAnswer) {
                $score += $question->points;
            }
        }

        $percentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;

        $this->attempt->update([
            'completed_at' => now(),
            'score'        => (int) round($percentage),
            'is_graded'    => true,
            'answers'      => $this->answers,
        ]);

        session()->flash('success', 'Quiz submitted! Your score: ' . round($percentage) . '%');
        return redirect()->route('cours.show', $this->course);
    }

    public function render()
    {
        $total = count($this->questions);

        return view('livewire.quizzes.take', [
            'questions'           => $this->questions,
            'currentQuestionData' => $this->questions[$this->currentQuestion] ?? null,
            'progress'            => $total > 0 ? (($this->currentQuestion + 1) / $total) * 100 : 0,
        ]);
    }
}
