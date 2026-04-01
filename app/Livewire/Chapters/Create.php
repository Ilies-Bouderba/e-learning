<?php

namespace App\Livewire\Chapters;

use App\Models\Attachment;
use App\Models\Chapter;
use App\Models\Cour;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public Cour $cour;

    public string $title = '';

    public string $content = '';

    public int $chapter_number = 1;

    public array $attachments = [];

    protected array $rules = [
        'title' => 'required|string|max:255',
        'content' => 'nullable|string',
        'chapter_number' => 'required|integer|min:1',
        'attachments.*.file' => 'nullable|file|max:10240',
        'attachments.*.title' => 'required_with:attachments.*.file|string|max:255',
        'attachments.*.type' => 'required|in:pdf,video,image,other',
    ];

    public function mount(Cour $cour)
    {
        if (! auth()->user()->isTeacher()) {
            abort(403);
        }
        if ($cour->teacher_id != auth()->id()) {
            abort(403);
        }

        $this->cour = $cour;
        $this->chapter_number = $cour->chapters()->count() + 1;
    }

    public function addAttachment()
    {
        $this->attachments[] = ['file' => null, 'title' => '', 'type' => 'pdf'];
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function save()
    {
        $this->validate();

        // Create the chapter
        $chapter = Chapter::create([
            'course_id' => $this->cour->id,
            'title' => $this->title,
            'content' => $this->content,
            'chapter_number' => $this->chapter_number,
        ]);

        // Save attachments
        foreach ($this->attachments as $attachment) {
            if (isset($attachment['file']) && $attachment['file']) {
                $path = $attachment['file']->store('attachments', 'public');
                Attachment::create([
                    'chapter_id' => $chapter->id,
                    'title' => $attachment['title'],
                    'type' => $attachment['type'],
                    'file_path' => $path,
                ]);
            }
        }

        session()->flash('success', 'Chapter added with '.count($this->attachments).' attachments.');

        return redirect()->route('cours.show', $this->cour);
    }

    public function render()
    {
        return view('livewire.chapters.create');
    }
}
