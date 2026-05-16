<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterComment extends Model
{
    use HasFactory;

    protected $fillable = ['chapter_id', 'student_id', 'comment_text'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'chapter_comment_id');
    }
}
