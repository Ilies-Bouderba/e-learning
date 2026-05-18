<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => 'string',
        ];
    }

    // ── Role helpers ──────────────────────────────────────────────────────────

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isTeacher(): bool  { return $this->role === 'teacher'; }
    public function isStudent(): bool  { return $this->role === 'student'; }

    // ── Relationships ─────────────────────────────────────────────────────────

    /** Courses this teacher owns */
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id');
    }

    // ── Business logic ────────────────────────────────────────────────────────

    /** Returns 0-100 progress percentage for a given course */
    public function courseProgress(int $courseId): int
    {
        $total     = Chapter::where('course_id', $courseId)->count();
        $completed = StudentProgress::where('student_id', $this->id)
            ->whereHas('chapter', fn ($q) => $q->where('course_id', $courseId))
            ->where('completed', true)
            ->count();

        return $total > 0 ? (int) round(($completed / $total) * 100) : 0;
    }

    /** Returns quiz status array for a given quiz */
    public function quizProgress(int $quizId): array
    {
        $attempt = QuizAttempt::where('student_id', $this->id)
            ->where('quiz_id', $quizId)
            ->first();

        if (! $attempt) {
            return ['status' => 'not_started', 'score' => null, 'percentage' => 0];
        }

        if (! $attempt->completed_at) {
            return ['status' => 'in_progress', 'score' => null, 'percentage' => 0];
        }

        return ['status' => 'completed', 'score' => $attempt->score, 'percentage' => (int) round($attempt->score)];
    }
}
