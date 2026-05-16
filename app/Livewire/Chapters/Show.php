<?php

namespace App\Livewire\Chapters;

use App\Models\Chapter;
use App\Models\Cour;
use App\Models\ChapterComment;
use App\Models\CommentReply;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Cour $cour;
    public Chapter $chapter;
    public $comments = [];
    public $newComment = '';
    public $replyText = [];
    public $showReplyForm = [];

    public function mount(Cour $cour, Chapter $chapter)
    {
        $user = auth()->user();

        if ($chapter->course_id != $cour->id) {
            abort(404, 'Chapter not found in this course.');
        }

        if ($user->isAdmin()) {
            $this->cour = $cour;
            $this->chapter = $chapter;
            $this->loadComments();
            return;
        }

        if ($user->isStudent()) {
            if (! $cour->enrollments()->where('student_id', $user->id)->exists()) {
                return redirect()->route('student.cours.enroll', $cour);
            }
        } elseif ($user->isTeacher()) {
            if ($cour->teacher_id != $user->id) {
                abort(403, 'You do not own this course.');
            }
        }

        $this->cour = $cour;
        $this->chapter = $chapter;
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = ChapterComment::where('chapter_id', $this->chapter->id)
            ->with(['student', 'replies.student'])
            ->latest()
            ->get();
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|min:2|max:1000',
        ]);

        if (!auth()->user()->isStudent() && !auth()->user()->isTeacher()) {
            session()->flash('error', 'Only students and teachers can comment.');
            return;
        }

        ChapterComment::create([
            'chapter_id' => $this->chapter->id,
            'student_id' => auth()->id(), // This field name is misleading but it stores user_id
            'comment_text' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->loadComments();
        session()->flash('success', 'Comment added!');
    }

    public function addReply($commentId)
    {
        $this->validate([
            "replyText.{$commentId}" => 'required|string|min:2|max:500',
        ]);

        // Allow both students AND teachers to reply
        if (!auth()->user()->isStudent() && !auth()->user()->isTeacher()) {
            session()->flash('error', 'Only students and teachers can reply.');
            return;
        }

        CommentReply::create([
            'chapter_comment_id' => $commentId,
            'student_id' => auth()->id(),
            'reply_text' => $this->replyText[$commentId],
        ]);

        $this->replyText[$commentId] = '';
        $this->showReplyForm[$commentId] = false;
        $this->loadComments();
        session()->flash('success', 'Reply added!');
    }

    public function toggleReplyForm($commentId)
    {
        $this->showReplyForm[$commentId] = !($this->showReplyForm[$commentId] ?? false);
    }

    public function render()
    {
        return view('livewire.chapters.show', [
            'cour' => $this->cour,
            'chapter' => $this->chapter->load('attachments'),
            'comments' => $this->comments,
        ]);
    }
}
