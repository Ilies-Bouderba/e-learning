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
class Create extends Component
{
    public Cour $cour;
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

    public function mount(Cour $cour)
    {
        $user = auth()->user();

        if (!$user->isTeacher()) {
            abort(403);
        }

        if ($cour->teacher_id != $user->id) {
            abort(403);
        }

        $this->cour = $cour;
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'id' => Str::random(10),
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
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function saveExam()
    {
        $this->validate();

        $startDate = null;
        $endDate = null;

        if ($this->start_date) {
            $startDate = Carbon::parse($this->start_date)->setTimezone('UTC');
        }

        if ($this->end_date) {
            $endDate = Carbon::parse($this->end_date)->setTimezone('UTC');
        }

        $exam = Exam::create([
            'course_id' => $this->cour->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_published' => true,
        ]);

        foreach ($this->questions as $questionData) {
            $exam->questions()->create([
                'question_text' => $questionData['question_text'],
                'points' => $questionData['points'],
                'order' => 0,
            ]);
        }

        $exam->calculateTotalPoints();

        session()->flash('success', 'Exam created successfully!');
        return redirect()->route('teacher.exams.index', $this->cour);
    }

    public function cancelQuestionForm()
    {
        $this->showQuestionForm = false;
        $this->currentQuestionIndex = null;
    }

    public function render()
    {
        return view('livewire.exams.create');
    }
}
