<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'content', 'posted_at'];
    protected $casts    = ['posted_at' => 'datetime'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
