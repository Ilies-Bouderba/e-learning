<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    use HasFactory;

    protected $fillable = ['chapter_comment_id', 'student_id', 'reply_text'];

    public function comment()
    {
        return $this->belongsTo(ChapterComment::class, 'chapter_comment_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
