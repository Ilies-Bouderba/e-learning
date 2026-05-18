<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * Renamed from Cour → Course for clarity.
 * The underlying table stays 'cours' to avoid a migration change.
 */
class Course extends Model
{
    use HasFactory;

    protected $table    = 'cours';
    protected $fillable = ['teacher_id', 'department_id', 'icon', 'title', 'description', 'password'];
    protected $hidden   = ['password'];

    // ── Relationships ─────────────────────────────────────────────────────────

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

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'course_id');
    }

    // ── Password helpers ──────────────────────────────────────────────────────

    public function hasPassword(): bool
    {
        return ! is_null($this->password);
    }

    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
