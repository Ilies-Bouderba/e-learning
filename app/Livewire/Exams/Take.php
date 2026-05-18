<?php

namespace App\Livewire\Exams;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\AIGradingService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Take extends Component
{
    public Course      $course;
    public Exam        $exam;
    public             $attempt         = null;
    public array       $answers         = [];
    public int         $currentQuestion = 0;
    public             $questions       = [];

    private AIGradingService $gradingService;

    public function boot(AIGradingService $gradingService): void
    {
        $this->gradingService = $gradingService;
    }

    public function mount(Course $course, Exam $exam): mixed
    {
        $student = auth()->user();

        if (! $student->isStudent()) {
            abort(403);
        }

        if (! $course->enrollments()->where('student_id', $student->id)->exists()) {
            return redirect()->route('student.cours.enroll', $course);
        }

        if (! $exam->is_published) {
            abort(403, 'This exam is not available yet.');
        }

        $now = Carbon::now();

        if ($exam->start_date && $now->lt($exam->start_date)) {
            abort(403, 'This exam starts on ' . $exam->start_date->format('F j, Y g:i A'));
        }

        if ($exam->end_date && $now->gt($exam->end_date)) {
            abort(403, 'This exam closed on ' . $exam->end_date->format('F j, Y g:i A'));
        }

        $this->course    = $course;
        $this->exam      = $exam;
        $this->questions = $exam->questions()->orderBy('order')->get();

        $this->attempt = ExamAttempt::firstOrCreate(
            ['student_id' => $student->id, 'exam_id' => $exam->id],
            ['started_at' => now(), 'answers' => []]
        );

        $this->loadAnswers();
        return null;
    }

    private function loadAnswers(): void
    {
        $this->answers = $this->attempt->answers
            ?: $this->questions->mapWithKeys(fn ($q) => [$q->id => ''])->toArray();
    }

    public function saveAnswer(int $questionId, string $answer): void
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

    public function submitExam(): mixed
    {
        $totalScore = 0;
        $grades     = [];

        foreach ($this->questions as $question) {
            $studentAnswer = $this->answers[$question->id] ?? '';

            $result = $this->gradingService->gradeAnswer(
                $question->question_text,
                $studentAnswer,
                $question->points
            );

            $grades[$question->id] = $result;
            $totalScore           += $result['score'];
        }

        $this->attempt->update([
            'completed_at' => now(),
            'answers'      => $this->answers,
            'ai_grades'    => $grades,
            'total_score'  => $totalScore,
            'is_graded'    => true,
        ]);

        $maxScore   = $this->exam->total_score;
        $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;

        session()->flash('success', "Exam submitted! Your score: {$percentage}%");
        return redirect()->route('cours.show', $this->course);
    }

    public function render()
    {
        $total = count($this->questions);

        return view('livewire.exams.take', [
            'questions'           => $this->questions,
            'currentQuestionData' => $this->questions[$this->currentQuestion] ?? null,
            'progress'            => $total > 0 ? (($this->currentQuestion + 1) / $total) * 100 : 0,
        ]);
    }
}
