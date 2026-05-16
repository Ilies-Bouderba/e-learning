<div class="dash-layout">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="sidebar-logo">edu<span>me</span>x</a>
        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Main</span>
            <a href="{{ route('teacher.dashboard') }}" class="sidebar-link active"><span class="sidebar-icon">🏠</span>
                Dashboard</a>
            <a href="{{ route('teacher.cours.index') }}" class="sidebar-link"><span class="sidebar-icon">📚</span> My Courses</a>
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('teacher.cours.create') }}" class="sidebar-link"><span class="sidebar-icon">➕</span> New
                Course</a>
        </nav>
        <div class="sidebar-user">
            <div class="sidebar-avatar sidebar-avatar-teacher">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="sidebar-user-info"><span class="sidebar-user-name">{{ auth()->user()->name }}</span><span
                    class="sidebar-user-role">Teacher</span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sidebar-logout"
                    title="Logout">↩</button></form>
        </div>
    </aside>
    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Welcome back, {{ auth()->user()->name }} 👋</h1>
                <p class="dash-subtitle">Here's an overview of your courses and students.</p>
            </div>
            <a href="{{ route('teacher.cours.create') }}" class="btn btn-primary">+ New Course</a>
        </div>
        <div class="dash-stats">
            <div class="dash-stat-card">
                <div class="dsc-icon">📚</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalCourses }}</span><span class="dsc-label">Active
                        Courses</span></div>
            </div>
            <div class="dash-stat-card dash-stat-yellow">
                <div class="dsc-icon">👥</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalStudents }}</span><span class="dsc-label">Total
                        Students</span></div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">📝</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalExams }}</span><span class="dsc-label">Exams
                        Created</span></div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">💬</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalComments }}</span><span
                        class="dsc-label">Comments</span></div>
            </div>
        </div>
        <div class="dash-grid">
            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">My Courses</h2><a href="{{ route('teacher.cours.index') }}"
                        class="dash-card-link">Manage all →</a>
                </div>
                <div class="teacher-courses">
                    @forelse($courses->take(3) as $course)
                        <div class="teacher-course-item">
                            <div class="tci-left"><span class="tci-icon">{{ $course->icon }}</span>
                                <div>
                                    <div class="tci-name">{{ $course->title }}</div>
                                    <div class="tci-meta">{{ $course->chapters_count }} chapters ·
                                        {{ $course->exams_count }} exams · {{ $course->department->name }}</div>
                                </div>
                            </div>
                            <div class="tci-stats">
                                <div class="tci-stat"><span
                                        class="tci-stat-num">{{ $course->enrollments_count }}</span><span
                                        class="tci-stat-label">Students</span></div>
                            </div>
                            <div class="tci-actions"><a href="{{ route('cours.show', $course) }}" class="btn-sm">View
                                    →</a></div>
                        </div>
                    @empty
                        <div class="mc-empty" style="padding:2rem 0;"><span>📭</span>
                            <p>No courses yet. <a href="{{ route('teacher.cours.create') }}">Create one →</a></p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Enrollments</h2>
                </div>
                <div class="students-list">
                    @forelse($recentStudents as $enrollment)
                        <div class="student-item">
                            <div class="student-avatar">{{ strtoupper(substr($enrollment->student->name, 0, 2)) }}</div>
                            <div class="student-info">
                                <div class="student-name">{{ $enrollment->student->name }}</div>
                                <div class="student-course">{{ $enrollment->course->title }}</div>
                            </div>
                            <div class="student-progress">
                                <div class="student-bar">
                                    <div class="student-fill" style="width:{{ $enrollment->progress_percentage }}%">
                                    </div>
                                </div><span class="student-pct">{{ $enrollment->progress_percentage }}%</span>
                            </div>
                        </div>
                    @empty<p class="empty-msg">No students yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Upcoming Exams</h2>
                </div>
                <div class="exam-list">
                    @forelse($upcomingExams as $exam)
                        <div class="exam-item {{ ($exam->start_date && $exam->start_date->diffInDays(now()) <= 3) ? 'exam-soon' : '' }}">
                            <div class="exam-item-top">
                                <span class="exam-badge {{ ($exam->start_date && $exam->start_date->diffInDays(now()) <= 3) ? 'exam-badge-soon' : '' }}">
                                    {{ $exam->start_date ? $exam->start_date->diffForHumans() : 'No date set' }}
                                </span>
                                <span class="exam-pts">{{ $exam->total_score }} pts</span>
                            </div>
                            <div class="exam-item-name">{{ $exam->title }}</div>
                            <div class="exam-item-meta">
                                @if($exam->duration_minutes) {{ $exam->duration_minutes }} min · @endif
                                {{ $exam->course->title }}
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No upcoming exams.</p>
                    @endforelse
                </div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Comments</h2>
                </div>
                <div class="comments-list">
                    @forelse($recentComments as $comment)
                        <div class="comment-item">
                            <div class="comment-avatar">{{ strtoupper(substr($comment->student->name, 0, 2)) }}</div>
                            <div class="comment-body">
                                <div class="comment-course">{{ $comment->course->title }}</div>
                                <div class="comment-text">{{ Str::limit($comment->comment_text, 90) }}</div>
                                <div class="comment-date">{{ $comment->posted_at ? $comment->posted_at->diffForHumans() : 'Unknown date' }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No comments yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">My Announcements</h2>
                </div>
                <div class="ann-list">
                    @forelse($recentAnnouncements as $ann)
                        <div class="ann-item">
                            <div class="ann-left"><span class="ann-icon">📢</span>
                                <div>
                                    <div class="ann-title">{{ $ann->title }}</div>
                                    <div class="ann-course">{{ $ann->course->title }}</div>
                                    <div class="ann-date">{{ $ann->posted_at ? $ann->posted_at->diffForHumans() : 'Unknown date' }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No announcements yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
