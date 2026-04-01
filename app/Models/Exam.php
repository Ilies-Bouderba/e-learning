<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'duration_minutes', 'scheduled_date', 'total_score'];

    protected $casts = ['scheduled_date' => 'datetime'];

    public function course()
    {
        return $this->belongsTo(Cour::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(StudentExam::class);
    }
}
