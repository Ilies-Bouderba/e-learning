<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'title', 'description', 'duration_minutes',
        'start_date', 'end_date', 'total_score', 'is_published'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Cour::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function calculateTotalPoints()
    {
        $total = $this->questions()->sum('points');
        $this->update(['total_score' => $total]);
        return $total;
    }
}
