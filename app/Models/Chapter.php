<?php

namespace App\Models;

use Database\Factories\ChapterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    /** @use HasFactory<ChapterFactory> */
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
}
