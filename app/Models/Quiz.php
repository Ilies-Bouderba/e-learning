<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected $fillable = [
        'course_id', 'title', 'description', 'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Cour::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }

    public function getTotalPoints()
    {
        return $this->questions()->sum('points');
    }
}
