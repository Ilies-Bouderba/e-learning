<?php

namespace App\Livewire\Chapters;

use App\Models\Attachment;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithFileUploads;

    public Course  $course;
    public Chapter $chapter;

    public string $title           = '';
    public string $content         = '';
    public        $newAttachment   = null;
    public string $attachmentTitle = '';
    public string $attachmentType  = 'pdf';

    protected array $rules = [
        'title'           => 'required|string|max:255',
        'content'         => 'nullable|string',
        'newAttachment'   => 'nullable|file|max:10240',
        'attachmentTitle' => 'required_with:newAttachment|string|max:255',
        'attachmentType'  => 'required|in:pdf,video,image,other',
    ];

    public function mount(Course $course, Chapter $chapter): void
    {
        $user = auth()->user();

        if (! $user->isTeacher() || (int) $course->teacher_id !== (int) $user->id) {
            abort(403);
        }

        if ((int) $chapter->course_id !== (int) $course->id) {
            abort(404);
        }

        $this->course   = $course;
        $this->chapter  = $chapter;
        $this->title    = $chapter->title;
        $this->content  = $chapter->content ?? '';
    }

    public function save(): mixed
    {
        $this->validate();

        $this->chapter->update([
            'title'   => $this->title,
            'content' => $this->content,
        ]);

        session()->flash('success', 'Chapter updated successfully.');
        return redirect()->route('chapters.show', ['course' => $this->course, 'chapter' => $this->chapter]);
    }

    public function addAttachment(): void
    {
        $this->validate([
            'newAttachment'   => 'required|file|max:10240',
            'attachmentTitle' => 'required|string|max:255',
            'attachmentType'  => 'required|in:pdf,video,image,other',
        ]);

        $path = $this->newAttachment->store('attachments', 'public');

        Attachment::create([
            'chapter_id' => $this->chapter->id,
            'title'      => $this->attachmentTitle,
            'type'       => $this->attachmentType,
            'file_path'  => $path,
        ]);

        $this->reset(['newAttachment', 'attachmentTitle']);
        $this->attachmentType = 'pdf';

        session()->flash('success', 'Attachment added successfully.');
    }

    public function deleteAttachment(int $attachmentId): void
    {
        $attachment = Attachment::where('id', $attachmentId)
            ->where('chapter_id', $this->chapter->id)
            ->firstOrFail();

        Storage::disk('public')->delete($attachment->file_path);
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
