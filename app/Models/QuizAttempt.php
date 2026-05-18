<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $table    = 'quiz_attempts';
    protected $fillable = ['student_id', 'quiz_id', 'started_at', 'completed_at', 'score', 'is_graded', 'answers'];
    protected $casts    = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'is_graded'    => 'boolean',
        'answers'      => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
