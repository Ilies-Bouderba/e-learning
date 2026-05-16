<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'chapter_number', 'content'];

    public function course()
    {
        return $this->belongsTo(Cour::class, 'course_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class);
    }

    public function isCompletedByStudent($studentId = null)
    {
        $studentId = $studentId ?? auth()->id();
        return $this->progress()->where('student_id', $studentId)->where('completed', true)->exists();
    }

    public function markAsCompleted($studentId = null)
    {
        $studentId = $studentId ?? auth()->id();

        return StudentProgress::updateOrCreate(
            ['student_id' => $studentId, 'chapter_id' => $this->id],
            ['completed' => true, 'completed_at' => now()]
        );
    }

    public function markAsIncomplete($studentId = null)
    {
        $studentId = $studentId ?? auth()->id();

        return StudentProgress::where('student_id', $studentId)
            ->where('chapter_id', $this->id)
            ->delete();
    }
}
