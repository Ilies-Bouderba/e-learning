<?php

namespace App\Livewire\Dashboard;

use App\Models\Announcement;
use App\Models\Comment;
use App\Models\Enrollment;
use App\Models\Exam;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Teacher extends Component
{
    public function render()
    {
        $teacher = auth()->user();
        $courseIds = $teacher->courses()->pluck('id');

        return view('livewire.dashboard.teacher', [
            'courses' => $teacher->courses()->with('department')->withCount(['enrollments', 'chapters', 'exams'])->latest()->get(),
            'totalCourses' => $teacher->courses()->count(),
            'totalStudents' => Enrollment::whereIn('course_id', $courseIds)->distinct('student_id')->count(),
            'totalExams' => Exam::whereIn('course_id', $courseIds)->count(),
            'totalComments' => Comment::whereIn('course_id', $courseIds)->count(),
            'recentStudents' => Enrollment::with(['student', 'course'])->whereIn('course_id', $courseIds)->latest()->take(6)->get(),
            'upcomingExams' => Exam::whereIn('course_id', $courseIds)->where('scheduled_date', '>=', now())->orderBy('scheduled_date')->take(4)->with('course')->get(),
            'recentAnnouncements' => Announcement::whereIn('course_id', $courseIds)->latest('posted_at')->take(5)->with('course')->get(),
            'recentComments' => Comment::whereIn('course_id', $courseIds)->latest('posted_at')->take(5)->with(['student', 'course'])->get(),
        ]);
    }
}
