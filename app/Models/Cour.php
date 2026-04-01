<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Cour extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'department_id', 'icon', 'title', 'description', 'password'];

    protected $hidden = ['password'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'course_id')->orderBy('chapter_number');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'course_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'course_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'course_id');
    }

    public function hasPassword(): bool
    {
        return ! is_null($this->password);
    }

    public function checkPassword(string $pw): bool
    {
        return Hash::check($pw, $this->password);
    }
}
