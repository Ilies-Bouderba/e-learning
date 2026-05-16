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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function courses()
    {
        return $this->hasMany(Cour::class, 'teacher_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Cour::class, 'enrollments', 'student_id', 'course_id');
    }

    public function courseProgress($courseId)
    {
        $totalChapters = Chapter::where('course_id', $courseId)->count();
        $completedChapters = StudentProgress::where('student_id', $this->id)
            ->whereHas('chapter', function($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->where('completed', true)
            ->count();

        return $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0;
    }

    public function quizProgress($quizId)
    {
        $attempt = QuizAttempt::where('student_id', $this->id)
            ->where('quiz_id', $quizId)
            ->first();

        if (!$attempt) {
            return ['status' => 'not_started', 'score' => null, 'percentage' => 0];
        }

        if (!$attempt->completed_at) {
            return ['status' => 'in_progress', 'score' => null, 'percentage' => 0];
        }

        return ['status' => 'completed', 'score' => $attempt->score, 'percentage' => round($attempt->score)];
    }
}
