<?php

namespace App\Livewire;

use App\Models\Cour;
use App\Models\Enrollment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Enroll extends Component
{
    public Cour $cour;

    public string $password = '';

    public function enroll()
    {
        if ($this->cour->hasPassword() && ! $this->cour->checkPassword($this->password)) {
            $this->addError('password', 'Wrong course password.');

            return;
        }

        Enrollment::create([
            'student_id' => auth()->id(),
            'course_id' => $this->cour->id,
        ]);

        return redirect()->route('cours.show', $this->cour);
    }

    public function render()
    {
        return view('livewire.enroll');
    }
}
