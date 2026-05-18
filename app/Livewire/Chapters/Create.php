<?php

namespace App\Livewire\Chapters;

use App\Models\Attachment;
use App\Models\Chapter;
use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public Course $course;
    public string $title          = '';
    public string $content        = '';
    public int    $chapter_number = 1;
    public array  $attachments    = [];

    protected array $rules = [
        'title'                    => 'required|string|max:255',
        'content'                  => 'nullable|string',
        'chapter_number'           => 'required|integer|min:1',
        'attachments.*.file'       => 'nullable|file|max:10240',
        'attachments.*.title'      => 'required_with:attachments.*.file|string|max:255',
        'attachments.*.type'       => 'required|in:pdf,video,image,other',
    ];

    public function mount(Course $course): void
    {
        $user = auth()->user();

        if (! $user->isTeacher() || (int) $course->teacher_id !== (int) $user->id) {
            abort(403);
        }

        $this->course         = $course;
        $this->chapter_number = $course->chapters()->count() + 1;
    }

    public function addAttachment(): void
    {
        $this->attachments[] = ['file' => null, 'title' => '', 'type' => 'pdf'];
    }

    public function removeAttachment(int $index): void
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function save(): mixed
    {
        $this->validate();

        $chapter = Chapter::create([
            'course_id'      => $this->course->id,
            'title'          => $this->title,
            'content'        => $this->content,
            'chapter_number' => $this->chapter_number,
        ]);

        foreach ($this->attachments as $item) {
            if (! empty($item['file'])) {
                $path = $item['file']->store('attachments', 'public');
                Attachment::create([
                    'chapter_id' => $chapter->id,
                    'title'      => $item['title'],
                    'type'       => $item['type'],
                    'file_path'  => $path,
                ]);
            }
        }

        session()->flash('success', 'Chapter added successfully.');
        return redirect()->route('cours.show', $this->course);
    }

    public function render()
    {
        return view('livewire.chapters.create');
    }
}
