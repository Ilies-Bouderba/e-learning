<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'chapter_number', 'content'];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function comments()
    {
        return $this->hasMany(ChapterComment::class);
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class);
    }

    // ── Progress helpers ──────────────────────────────────────────────────────

    public function isCompletedBy(?int $studentId = null): bool
    {
        $studentId = $studentId ?? auth()->id();

        return $this->progress()
            ->where('student_id', $studentId)
            ->where('completed', true)
            ->exists();
    }

    public function markCompleted(?int $studentId = null): StudentProgress
    {
        $studentId = $studentId ?? auth()->id();

        return StudentProgress::updateOrCreate(
            ['student_id' => $studentId, 'chapter_id' => $this->id],
            ['completed' => true, 'completed_at' => now()]
        );
    }

    public function markIncomplete(?int $studentId = null): void
    {
        $studentId = $studentId ?? auth()->id();

        StudentProgress::where('student_id', $studentId)
            ->where('chapter_id', $this->id)
            ->delete();
    }
}
