<?php

namespace App\Livewire\Chapters;

use App\Models\Attachment;
use App\Models\Chapter;
use App\Models\Cour;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithFileUploads;

    public Cour $cour;

    public Chapter $chapter;

    public string $title = '';

    public string $content = '';

    public $newAttachment;

    public string $attachmentTitle = '';

    public string $attachmentType = 'pdf';

    protected array $rules = [
        'title' => 'required|string|max:255',
        'content' => 'nullable|string',
        'newAttachment' => 'nullable|file|max:10240', // Max 10MB
        'attachmentTitle' => 'required_with:newAttachment|string|max:255',
        'attachmentType' => 'required|in:pdf,video,image,other',
    ];

    public function mount(Cour $cour, Chapter $chapter)
    {
        if (! auth()->user()->isTeacher()) {
            abort(403);
        }

        if ($cour->teacher_id != auth()->id()) {
            abort(403);
        }

        if ($chapter->course_id != $cour->id) {
            abort(404);
        }

        $this->cour = $cour;
        $this->chapter = $chapter;
        $this->title = $chapter->title;
        $this->content = $chapter->content;
    }

    public function save()
    {
        $this->validate();

        $this->chapter->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        session()->flash('success', 'Chapter updated successfully.');

        return redirect()->route('chapters.show', ['cour' => $this->cour, 'chapter' => $this->chapter]);
    }

    public function addAttachment()
    {
        $this->validate([
            'newAttachment' => 'required|file|max:10240',
            'attachmentTitle' => 'required|string|max:255',
            'attachmentType' => 'required|in:pdf,video,image,other',
        ]);

        $path = $this->newAttachment->store('attachments', 'public');

        Attachment::create([
            'chapter_id' => $this->chapter->id,
            'title' => $this->attachmentTitle,
            'type' => $this->attachmentType,
            'file_path' => $path,
        ]);

        $this->reset(['newAttachment', 'attachmentTitle']);
        $this->attachmentType = 'pdf';

        session()->flash('success', 'Attachment added successfully.');
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = Attachment::where('id', $attachmentId)
            ->where('chapter_id', $this->chapter->id)
            ->firstOrFail();

        // Delete file from storage
        \Storage::disk('public')->delete($attachment->file_path);

        $attachment->delete();

        session()->flash('success', 'Attachment deleted.');
    }

    public function render()
    {
        return view('livewire.chapters.edit', [
            'attachments' => $this->chapter->attachments,
        ]);
    }
}
