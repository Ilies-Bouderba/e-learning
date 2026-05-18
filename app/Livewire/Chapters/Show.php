<?php

namespace App\Livewire\Chapters;

use App\Models\Chapter;
use App\Models\ChapterComment;
use App\Models\CommentReply;
use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Course  $course;
    public Chapter $chapter;

    public        $comments       = [];
    public string $newComment     = '';
    public array  $replyText      = [];
    public array  $showReplyForm  = [];

    public function mount(Course $course, Chapter $chapter): mixed
    {
        if ((int) $chapter->course_id !== (int) $course->id) {
            abort(404, 'Chapter not found in this course.');
        }

        $user = auth()->user();

        if ($user->isStudent() && ! $course->enrollments()->where('student_id', $user->id)->exists()) {
            return redirect()->route('student.cours.enroll', $course);
        }

        if ($user->isTeacher() && (int) $course->teacher_id !== (int) $user->id) {
            abort(403, 'You do not own this course.');
        }

        $this->course  = $course;
        $this->chapter = $chapter;
        $this->loadComments();

        return null;
    }

    public function loadComments(): void
    {
        $this->comments = ChapterComment::where('chapter_id', $this->chapter->id)
            ->with(['author', 'replies.author'])
            ->latest()
            ->get();
    }

    public function addComment(): void
    {
        $this->validate(['newComment' => 'required|string|min:2|max:1000']);

        $user = auth()->user();
        if (! $user->isStudent() && ! $user->isTeacher()) {
            session()->flash('error', 'Only students and teachers can comment.');
            return;
        }

        ChapterComment::create([
            'chapter_id'   => $this->chapter->id,
            'student_id'   => $user->id,
            'comment_text' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->loadComments();
        session()->flash('success', 'Comment added!');
    }

    public function addReply(int $commentId): void
    {
        $this->validate(["replyText.{$commentId}" => 'required|string|min:2|max:500']);

        $user = auth()->user();
        if (! $user->isStudent() && ! $user->isTeacher()) {
            session()->flash('error', 'Only students and teachers can reply.');
            return;
        }

        CommentReply::create([
            'chapter_comment_id' => $commentId,
            'student_id'         => $user->id,
            'reply_text'         => $this->replyText[$commentId],
        ]);

        $this->replyText[$commentId]     = '';
        $this->showReplyForm[$commentId] = false;
        $this->loadComments();
        session()->flash('success', 'Reply added!');
    }

    public function toggleReplyForm(int $commentId): void
    {
        $this->showReplyForm[$commentId] = ! ($this->showReplyForm[$commentId] ?? false);
    }

    public function render()
    {
        return view('livewire.chapters.show', [
            'course'   => $this->course,
            'chapter'  => $this->chapter->load('attachments'),
            'comments' => $this->comments,
        ]);
    }
}
