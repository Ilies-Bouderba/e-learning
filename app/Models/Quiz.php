<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table    = 'quizzes';
    protected $fillable = ['course_id', 'title', 'description', 'is_published'];
    protected $casts    = ['is_published' => 'boolean'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }

    public function totalPoints(): int
    {
        return (int) $this->questions()->sum('points');
    }
}
