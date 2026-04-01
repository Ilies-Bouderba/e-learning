<?php

namespace App\Livewire;

use App\Models\Cour;
use Livewire\Component;

class CourseSidebar extends Component
{
    public Cour $cour;

    public string $active = 'chapters';

    public function mount(Cour $cour, string $active = 'chapters')
    {
        $this->cour = $cour;
        $this->active = $active;
    }

    public function render()
    {
        return view('livewire.course-sidebar');
    }
}
