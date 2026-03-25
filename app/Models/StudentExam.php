<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentExam extends Model {

    use HasFactory;

    protected $fillable = ['student_id', 'exam_id', 'answers', 'score', 'completed_at'];
    protected $casts    = ['completed_at' => 'datetime'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
