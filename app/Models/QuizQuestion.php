<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table    = 'quiz_questions';
    protected $fillable = ['quiz_id', 'question_text', 'points', 'order'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_question_id');
    }

    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
