<?php

namespace App\Livewire\Exams;

use App\Models\Cour;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\AIGradingService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Take extends Component
{
    public Cour $cour;
    public Exam $exam;
    public $attempt = null;
    public $answers = [];
    public $currentQuestion = 0;
    public $questions = [];

    protected $aiGradingService;

    public function boot(AIGradingService $aiGradingService)
    {
        $this->aiGradingService = $aiGradingService;
    }

    public function mount(Cour $cour, Exam $exam)
    {
        $student = auth()->user();

        if (!$student->isStudent()) {
            abort(403);
        }

        if (!$cour->enrollments()->where('student_id', $student->id)->exists()) {
            return redirect()->route('student.cours.enroll', $cour);
        }

        if (!$exam->is_published) {
            abort(403, 'This exam is not available yet.');
        }

        // Check if exam is within available date range
        $now = Carbon::now();

        if ($exam->start_date && $now->lt(Carbon::parse($exam->start_date))) {
            abort(403, 'This exam starts on ' . Carbon::parse($exam->start_date)->format('F j, Y g:i A'));
        }

        if ($exam->end_date && $now->gt(Carbon::parse($exam->end_date))) {
            abort(403, 'This exam closed on ' . Carbon::parse($exam->end_date)->format('F j, Y g:i A'));
        }

        $this->cour = $cour;
        $this->exam = $exam;
        $this->questions = $exam->questions()->orderBy('order')->get();

        $this->attempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->first();

        if (!$this->attempt) {
            $this->attempt = ExamAttempt::create([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'started_at' => now(),
                'answers' => [],
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
                $this->answers[$question->id] = '';
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

    public function submitExam()
    {
        $totalScore = 0;
        $grades = [];

        foreach ($this->questions as $question) {
            $studentAnswer = $this->answers[$question->id] ?? '';

            // Grade with AI
            $gradingResult = $this->aiGradingService->gradeAnswer(
                $question->question_text,
                $studentAnswer,
                $question->points
            );

            $grades[$question->id] = $gradingResult;
            $totalScore += $gradingResult['score'];
        }

        $this->attempt->update([
            'completed_at' => now(),
            'answers' => $this->answers,
            'ai_grades' => $grades,
            'total_score' => $totalScore,
            'is_graded' => true,
        ]);

        $percentageScore = $this->exam->total_score > 0 ? ($totalScore / $this->exam->total_score) * 100 : 0;

        session()->flash('success', "Exam submitted! Your score: " . round($percentageScore) . "%");
        return redirect()->route('cours.show', $this->cour);
    }

    public function render()
    {
        return view('livewire.exams.take', [
            'questions' => $this->questions,
            'currentQuestionData' => $this->questions[$this->currentQuestion] ?? null,
            'progress' => count($this->questions) > 0 ? (($this->currentQuestion + 1) / count($this->questions)) * 100 : 0,
        ]);
    }
}
