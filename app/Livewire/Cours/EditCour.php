<?php
// app/Livewire/Cours/EditCour.php
namespace App\Livewire\Cours;

use App\Models\Cour;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.app')]
class EditCour extends Component
{
    public Cour $cour;

    public string $title        = '';
    public string $description  = '';
    public string $icon         = '📚';
    public ?int   $department_id = null;
    public string $password     = '';
    public bool   $has_password = false;

    public array $icons = ['📚', '🔬', '📐', '💻', '🌍', '🎨', '⚗️', '📖', '🧬', '🎵', '🏛️', '🧮'];

    protected array $rules = [
        'title'         => 'required|string|max:255',
        'description'   => 'nullable|string|max:1000',
        'icon'          => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'password'      => 'nullable|string|min:4',
    ];

    public function mount(Cour $cour)
    {
        $this->authorize('teacher');
        abort_if($cour->teacher_id !== auth()->id(), 403);

        $this->cour          = $cour;
        $this->title         = $cour->title;
        $this->description   = $cour->description ?? '';
        $this->icon          = $cour->icon;
        $this->department_id = $cour->department_id;
        $this->has_password  = $cour->hasPassword();
    }

    public function save()
    {
        $this->validate();

        $this->cour->update([
            'department_id' => $this->department_id,
            'icon'          => $this->icon,
            'title'         => $this->title,
            'description'   => $this->description,
            'password'      => $this->has_password && $this->password
                                ? Hash::make($this->password)
                                : ($this->has_password ? $this->cour->getRawOriginal('password') : null),
        ]);

        session()->flash('success', 'Course updated successfully.');
        return redirect()->route('cours.index');
    }

    public function render()
    {
        return view('livewire.cours.edit-cour', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }
}
