<?php

namespace App\Livewire\Cours;

use App\Models\Course;
use App\Models\Enrollment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Enroll extends Component
{
    public Course $course;
    public string $password = '';

    public function enroll(): mixed
    {
        if ($this->course->hasPassword() && ! $this->course->checkPassword($this->password)) {
            $this->addError('password', 'Wrong course password.');
            return null;
        }

        Enrollment::create([
            'student_id' => auth()->id(),
            'course_id'  => $this->course->id,
        ]);

        return redirect()->route('cours.show', $this->course);
    }

    public function render()
    {
        return view('livewire.cours.enroll');
    }
}
