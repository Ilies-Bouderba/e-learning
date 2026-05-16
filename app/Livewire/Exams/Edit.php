<?php

namespace App\Livewire\Exams;

use App\Models\Cour;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Cour $cour;
    public Exam $exam;
    public $title = '';
    public $description = '';
    public $duration_minutes = '';
    public $start_date = '';
    public $end_date = '';

    public $questions = [];
    public $currentQuestionIndex = 0;
    public $showQuestionForm = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'duration_minutes' => 'nullable|integer|min:1|max:300',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
    ];

    public function mount(Cour $cour, Exam $exam)
    {
        $user = auth()->user();

        if (!$user->isTeacher()) {
            abort(403);
        }

        if ($cour->teacher_id != $user->id) {
            abort(403);
        }

        if ($exam->course_id != $cour->id) {
            abort(404);
        }

        $this->cour = $cour;
        $this->exam = $exam;
        $this->title = $exam->title;
        $this->description = $exam->description ?? '';
        $this->duration_minutes = $exam->duration_minutes;
        $this->start_date = $exam->start_date ? Carbon::parse($exam->start_date)->format('Y-m-d\TH:i') : '';
        $this->end_date = $exam->end_date ? Carbon::parse($exam->end_date)->format('Y-m-d\TH:i') : '';

        $this->loadQuestions();
    }

    public function loadQuestions()
    {
        $dbQuestions = $this->exam->questions()->orderBy('order')->get();

        foreach ($dbQuestions as $dbQuestion) {
            $this->questions[] = [
                'id' => $dbQuestion->id,
                'temp_id' => Str::random(10),
                'question_text' => $dbQuestion->question_text,
                'points' => $dbQuestion->points,
            ];
        }
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'id' => null,
            'temp_id' => Str::random(10),
            'question_text' => '',
            'points' => 10,
        ];
        $this->currentQuestionIndex = count($this->questions) - 1;
        $this->showQuestionForm = true;
    }

    public function editQuestion($index)
    {
        $this->currentQuestionIndex = $index;
        $this->showQuestionForm = true;
    }

    public function removeQuestion($index)
    {
        $question = $this->questions[$index];

        if (isset($question['id']) && $question['id']) {
            ExamQuestion::where('id', $question['id'])->delete();
        }

        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
        session()->flash('success', 'Question removed.');
    }

    public function saveExam()
    {
        $this->validate();

        // Convert dates to proper format for SQL Server
        $startDate = null;
        $endDate = null;

        if ($this->start_date) {
            // Create Carbon instance from local time and convert to UTC for storage
            $startDate = Carbon::parse($this->start_date)->setTimezone('UTC');
        }

        if ($this->end_date) {
            $endDate = Carbon::parse($this->end_date)->setTimezone('UTC');
        }

        $this->exam->update([
            'title' => $this->title,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Delete existing questions that are no longer present
        $existingIds = collect($this->questions)->filter(function($q) {
            return isset($q['id']) && $q['id'];
        })->pluck('id')->toArray();

        $this->exam->questions()->whereNotIn('id', $existingIds)->delete();

        // Update or create questions
        foreach ($this->questions as $questionData) {
            if (isset($questionData['id']) && $questionData['id']) {
                $question = ExamQuestion::find($questionData['id']);
                if ($question) {
                    $question->update([
                        'question_text' => $questionData['question_text'],
                        'points' => $questionData['points'],
                    ]);
                }
            } else {
                $this->exam->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'points' => $questionData['points'],
                    'order' => 0,
                ]);
            }
        }

        $this->exam->calculateTotalPoints();

        session()->flash('success', 'Exam updated successfully!');
        return redirect()->route('teacher.exams.index', $this->cour);
    }

    public function cancelQuestionForm()
    {
        $this->showQuestionForm = false;
        $this->currentQuestionIndex = null;
    }

    public function render()
    {
        return view('livewire.exams.edit');
    }
}
