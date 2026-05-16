<div class="dash-layout">
    <aside class="sidebar">
        <a href="/" class="sidebar-logo">edu<span>me</span>x</a>
        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Main</span>
            {{-- FIXED: changed from dashboard.student to student.dashboard --}}
            <a href="{{ route('student.dashboard') }}" class="sidebar-link active">
                <span class="sidebar-icon">🏠</span> Dashboard
            </a>
            <a href="{{ route('student.cours.index') }}" class="sidebar-link">
                <span class="sidebar-icon">📚</span> My Courses
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">📝</span> Exams
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">📊</span> Progress
            </a>
            <span class="sidebar-nav-label">Other</span>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">📢</span> Announcements
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">💬</span> Comments
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">⚙️</span> Settings
            </a>
        </nav>
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                <span class="sidebar-user-role">Student</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sidebar-logout" title="Logout">↩</button></form>
        </div>
    </aside>

    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Good {{ \Carbon\Carbon::now()->format('l') }}! {{ auth()->user()->name }} 👋</h1>
                <p class="dash-subtitle">Here's what's happening with your learning today.</p>
            </div>
            <a href="{{ route('student.cours.index') }}" class="btn btn-primary">Browse Courses →</a>
        </div>

        @php
            $enrolledCourses = auth()->user()->enrolledCourses()->with(['teacher', 'chapters', 'enrollments' => function($q) {
                $q->where('student_id', auth()->id());
            }])->get();

            $totalChaptersCompleted = 0;
            foreach($enrolledCourses as $course) {
                $enrollment = $course->enrollments->first();
                if($enrollment) {
                    $totalChaptersCompleted += floor(($enrollment->progress_percentage / 100) * $course->chapters->count());
                }
            }

            $totalQuizzesTaken = \App\Models\QuizAttempt::where('student_id', auth()->id())
                ->where('completed_at', '!=', null)
                ->count();

            $avgScore = \App\Models\QuizAttempt::where('student_id', auth()->id())
                ->where('completed_at', '!=', null)
                ->avg('score') ?? 0;

            $recentQuizAttempts = \App\Models\QuizAttempt::where('student_id', auth()->id())
                ->where('completed_at', '!=', null)
                ->with('quiz')
                ->latest('completed_at')
                ->take(4)
                ->get();

            $recentAnnouncements = [];
            foreach($enrolledCourses as $course) {
                foreach($course->announcements()->latest('posted_at')->take(2)->get() as $ann) {
                    $ann->course_title = $course->title;
                    $recentAnnouncements[] = $ann;
                }
            }
            $recentAnnouncements = collect($recentAnnouncements)->sortByDesc('posted_at')->take(4);
        @endphp

        <div class="dash-stats">
            <div class="dash-stat-card">
                <div class="dsc-icon">📚</div>
                <div class="dsc-info">
                    <span class="dsc-num">{{ $enrolledCourses->count() }}</span>
                    <span class="dsc-label">Enrolled Courses</span>
                </div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">✅</div>
                <div class="dsc-info">
                    <span class="dsc-num">{{ $totalChaptersCompleted }}</span>
                    <span class="dsc-label">Chapters Done</span>
                </div>
            </div>
            <div class="dash-stat-card dash-stat-yellow">
                <div class="dsc-icon">🏆</div>
                <div class="dsc-info">
                    <span class="dsc-num">{{ round($avgScore) }}</span>
                    <span class="dsc-label">Avg. Quiz Score</span>
                </div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">📝</div>
                <div class="dsc-info">
                    <span class="dsc-num">{{ $totalQuizzesTaken }}</span>
                    <span class="dsc-label">Quizzes Taken</span>
                </div>
            </div>
        </div>

        <div class="dash-grid">
            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">My Courses</h2>
                    <a href="{{ route('student.cours.index') }}" class="dash-card-link">View all →</a>
                </div>
                @if($enrolledCourses->count() > 0)
                    <div class="enrolled-list">
                        @foreach($enrolledCourses as $course)
                            @php
                                $enrollment = $course->enrollments->first();
                                $progress = $enrollment ? $enrollment->progress_percentage : 0;
                                $chaptersCompleted = floor(($progress / 100) * $course->chapters->count());
                                $totalChapters = $course->chapters->count();
                            @endphp
                            <div class="enrolled-item">
                                <div class="enrolled-icon">{{ $course->icon }}</div>
                                <div class="enrolled-info">
                                    <div class="enrolled-name">{{ $course->title }}</div>
                                    <div class="enrolled-meta">{{ $totalChapters }} chapters · {{ $course->teacher->name }}</div>
                                    <div class="enrolled-bar">
                                        <div class="enrolled-fill" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                <div class="enrolled-right">
                                    <span class="enrolled-pct">{{ $progress }}%</span>
                                    <a href="{{ route('cours.show', $course) }}" class="btn-sm">Continue →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mc-empty">
                        <span>📭</span>
                        <p>You're not enrolled in any courses yet.</p>
                        <a href="{{ route('student.cours.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Browse Courses →</a>
                    </div>
                @endif
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Quiz Scores</h2>
                </div>
                @if($recentQuizAttempts->count() > 0)
                    <div class="scores-list">
                        @foreach($recentQuizAttempts as $attempt)
                            @php
                                $scoreClass = $attempt->score >= 70 ? 'score-high' : ($attempt->score >= 50 ? 'score-mid' : 'score-low');
                            @endphp
                            <div class="score-item">
                                <div class="score-left">
                                    <div class="score-name">{{ $attempt->quiz->title }}</div>
                                    <div class="score-course">{{ $attempt->quiz->course->title ?? 'Course' }}</div>
                                </div>
                                <div class="score-badge {{ $scoreClass }}">{{ round($attempt->score) }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mc-empty" style="padding: 2rem;">
                        <span>📊</span>
                        <p>No quiz attempts yet.</p>
                        <a href="{{ route('student.cours.index') }}" class="btn-sm" style="margin-top: 0.5rem;">Take a quiz →</a>
                    </div>
                @endif
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Course Progress</h2>
                </div>
                @if($enrolledCourses->count() > 0)
                    <div class="scores-list">
                        @foreach($enrolledCourses->take(4) as $course)
                            @php
                                $enrollment = $course->enrollments->first();
                                $progress = $enrollment ? $enrollment->progress_percentage : 0;
                            @endphp
                            <div class="score-item">
                                <div class="score-left">
                                    <div class="score-name">{{ $course->title }}</div>
                                    <div class="score-course">{{ $course->chapters->count() }} chapters</div>
                                </div>
                                <div class="score-badge" style="background: var(--c-yellow);">{{ $progress }}%</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mc-empty" style="padding: 2rem;">
                        <span>📈</span>
                        <p>Enroll in a course to see progress.</p>
                    </div>
                @endif
            </div>

            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Latest Announcements</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                @if($recentAnnouncements->count() > 0)
                    <div class="ann-list">
                        @foreach($recentAnnouncements as $ann)
                            <div class="ann-item">
                                <div class="ann-left">
                                    <span class="ann-icon">📢</span>
                                    <div>
                                        <div class="ann-title">{{ $ann->title }}</div>
                                        <div class="ann-course">{{ $ann->course_title ?? 'Course' }}</div>
                                        <div class="ann-course" style="font-size: 0.7rem; margin-top: 0.25rem;">{{ Str::limit($ann->content, 80) }}</div>
                                    </div>
                                </div>
                                <span class="ann-date">{{ $ann->posted_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mc-empty" style="padding: 2rem;">
                        <span>📢</span>
                        <p>No announcements yet.</p>
                    </div>
                @endif
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Comments</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="comments-list">
                    <div class="comment-item">
                        <div class="comment-avatar">💬</div>
                        <div class="comment-body">
                            <div class="comment-course">Coming Soon</div>
                            <div class="comment-text">Comment system will be available soon.</div>
                            <div class="comment-date">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Resources</h2>
                </div>
                <div class="attach-list">
                    <div class="attach-item">
                        <div class="attach-type">📁</div>
                        <div class="attach-info">
                            <div class="attach-name">Resources coming soon</div>
                            <div class="attach-course">Check back later</div>
                        </div>
                        <a href="#" class="attach-dl">↓</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
