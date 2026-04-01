<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'chapter_id', 'completed', 'completed_at'];

    protected $casts = ['completed' => 'boolean', 'completed_at' => 'datetime'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
