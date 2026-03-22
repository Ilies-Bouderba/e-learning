{{-- resources/views/livewire/dashboard/teacher.blade.php --}}

<div class="dash-layout">

    {{-- ========== SIDEBAR ========== --}}
    <aside class="sidebar">
        <a href="/" class="sidebar-logo">edu<span>me</span>x</a>

        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Main</span>
            <a href="#" class="sidebar-link active">
                <span class="sidebar-icon">🏠</span> Dashboard
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">📚</span> My Courses
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">👥</span> Students
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">📝</span> Exams
            </a>

            <span class="sidebar-nav-label">Manage</span>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">📢</span> Announcements
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">📎</span> Attachments
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">💬</span> Comments
            </a>
            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">⚙️</span> Settings
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-avatar sidebar-avatar-teacher">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                <span class="sidebar-user-role">Teacher</span>
            </div>
            <a href="#" class="sidebar-logout" title="Logout">↩</a>
        </div>
    </aside>

    {{-- ========== MAIN CONTENT ========== --}}
    <main class="dash-main">

        {{-- Header --}}
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Welcome back, {{ auth()->user()->name }} 👋</h1>
                <p class="dash-subtitle">Here's an overview of your courses and students.</p>
            </div>
                <a href="{{ route("cours.create") }}" class="btn btn-primary">+ New Course</a>
        </div>

        {{-- Stats row --}}
        <div class="dash-stats">
            <div class="dash-stat-card">
                <div class="dsc-icon">📚</div>
                <div class="dsc-info">
                    <span class="dsc-num">3</span>
                    <span class="dsc-label">Active Courses</span>
                </div>
            </div>
            <div class="dash-stat-card dash-stat-yellow">
                <div class="dsc-icon">👥</div>
                <div class="dsc-info">
                    <span class="dsc-num">142</span>
                    <span class="dsc-label">Total Students</span>
                </div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">📝</div>
                <div class="dsc-info">
                    <span class="dsc-num">8</span>
                    <span class="dsc-label">Exams Created</span>
                </div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">💬</div>
                <div class="dsc-info">
                    <span class="dsc-num">24</span>
                    <span class="dsc-label">Pending Comments</span>
                </div>
            </div>
        </div>

        <div class="dash-grid">

            {{-- ── My Courses ── --}}
            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">My Courses</h2>
                    <a href="#" class="dash-card-link">Manage all →</a>
                </div>
                <div class="teacher-courses">

                    <div class="teacher-course-item">
                        <div class="tci-left">
                            <span class="tci-icon">📐</span>
                            <div>
                                <div class="tci-name">Advanced Mathematics</div>
                                <div class="tci-meta">9 chapters · 8 exams</div>
                            </div>
                        </div>
                        <div class="tci-stats">
                            <div class="tci-stat">
                                <span class="tci-stat-num">68</span>
                                <span class="tci-stat-label">Students</span>
                            </div>
                            <div class="tci-stat">
                                <span class="tci-stat-num">74%</span>
                                <span class="tci-stat-label">Avg Progress</span>
                            </div>
                            <div class="tci-stat">
                                <span class="tci-stat-num">82</span>
                                <span class="tci-stat-label">Avg Score</span>
                            </div>
                        </div>
                        <div class="tci-actions">
                            <a href="#" class="btn-sm">Manage →</a>
                        </div>
                    </div>

                    <div class="teacher-course-item">
                        <div class="tci-left">
                            <span class="tci-icon">🔢</span>
                            <div>
                                <div class="tci-name">Statistics & Probability</div>
                                <div class="tci-meta">7 chapters · 5 exams</div>
                            </div>
                        </div>
                        <div class="tci-stats">
                            <div class="tci-stat">
                                <span class="tci-stat-num">45</span>
                                <span class="tci-stat-label">Students</span>
                            </div>
                            <div class="tci-stat">
                                <span class="tci-stat-num">58%</span>
                                <span class="tci-stat-label">Avg Progress</span>
                            </div>
                            <div class="tci-stat">
                                <span class="tci-stat-num">78</span>
                                <span class="tci-stat-label">Avg Score</span>
                            </div>
                        </div>
                        <div class="tci-actions">
                            <a href="#" class="btn-sm">Manage →</a>
                        </div>
                    </div>

                    <div class="teacher-course-item">
                        <div class="tci-left">
                            <span class="tci-icon">📊</span>
                            <div>
                                <div class="tci-name">Linear Algebra</div>
                                <div class="tci-meta">6 chapters · 4 exams</div>
                            </div>
                        </div>
                        <div class="tci-stats">
                            <div class="tci-stat">
                                <span class="tci-stat-num">29</span>
                                <span class="tci-stat-label">Students</span>
                            </div>
                            <div class="tci-stat">
                                <span class="tci-stat-num">41%</span>
                                <span class="tci-stat-label">Avg Progress</span>
                            </div>
                            <div class="tci-stat">
                                <span class="tci-stat-num">71</span>
                                <span class="tci-stat-label">Avg Score</span>
                            </div>
                        </div>
                        <div class="tci-actions">
                            <a href="#" class="btn-sm">Manage →</a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Recent Students ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Students</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="students-list">

                    <div class="student-item">
                        <div class="student-avatar">SA</div>
                        <div class="student-info">
                            <div class="student-name">Sara Ahmed</div>
                            <div class="student-course">Advanced Mathematics</div>
                        </div>
                        <div class="student-progress">
                            <div class="student-bar">
                                <div class="student-fill" style="width:45%"></div>
                            </div>
                            <span class="student-pct">45%</span>
                        </div>
                    </div>

                    <div class="student-item">
                        <div class="student-avatar">MB</div>
                        <div class="student-info">
                            <div class="student-name">Mohammed Benali</div>
                            <div class="student-course">Statistics & Probability</div>
                        </div>
                        <div class="student-progress">
                            <div class="student-bar">
                                <div class="student-fill" style="width:80%"></div>
                            </div>
                            <span class="student-pct">80%</span>
                        </div>
                    </div>

                    <div class="student-item">
                        <div class="student-avatar">FZ</div>
                        <div class="student-info">
                            <div class="student-name">Fatima Zahra</div>
                            <div class="student-course">Linear Algebra</div>
                        </div>
                        <div class="student-progress">
                            <div class="student-bar">
                                <div class="student-fill" style="width:30%"></div>
                            </div>
                            <span class="student-pct">30%</span>
                        </div>
                    </div>

                    <div class="student-item">
                        <div class="student-avatar">AK</div>
                        <div class="student-info">
                            <div class="student-name">Amine Khelil</div>
                            <div class="student-course">Advanced Mathematics</div>
                        </div>
                        <div class="student-progress">
                            <div class="student-bar">
                                <div class="student-fill" style="width:92%"></div>
                            </div>
                            <span class="student-pct">92%</span>
                        </div>
                    </div>

                    <div class="student-item">
                        <div class="student-avatar">NB</div>
                        <div class="student-info">
                            <div class="student-name">Nour Bensalem</div>
                            <div class="student-course">Statistics & Probability</div>
                        </div>
                        <div class="student-progress">
                            <div class="student-bar">
                                <div class="student-fill" style="width:61%"></div>
                            </div>
                            <span class="student-pct">61%</span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Upcoming Exams ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Upcoming Exams</h2>
                    <a href="#" class="dash-card-link">Manage →</a>
                </div>
                <div class="exam-list">

                    <div class="exam-item exam-soon">
                        <div class="exam-item-top">
                            <span class="exam-badge exam-badge-soon">In 2 days</span>
                            <span class="exam-pts">100 pts</span>
                        </div>
                        <div class="exam-item-name">Calculus Final</div>
                        <div class="exam-item-meta">120 min · Advanced Mathematics · 68 students</div>
                    </div>

                    <div class="exam-item">
                        <div class="exam-item-top">
                            <span class="exam-badge">In 1 week</span>
                            <span class="exam-pts">60 pts</span>
                        </div>
                        <div class="exam-item-name">Stats Quiz #3</div>
                        <div class="exam-item-meta">45 min · Statistics & Probability · 45 students</div>
                    </div>

                    <div class="exam-item">
                        <div class="exam-item-top">
                            <span class="exam-badge">In 2 weeks</span>
                            <span class="exam-pts">80 pts</span>
                        </div>
                        <div class="exam-item-name">Linear Algebra Mid-Term</div>
                        <div class="exam-item-meta">90 min · Linear Algebra · 29 students</div>
                    </div>

                </div>
            </div>

            {{-- ── Recent Exam Results ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Exam Results</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="results-list">

                    <div class="result-item">
                        <div class="result-left">
                            <div class="result-name">Stats Quiz #2</div>
                            <div class="result-meta">45 students attempted</div>
                        </div>
                        <div class="result-right">
                            <span class="result-avg">Avg: 79</span>
                            <span class="result-pass">38 passed</span>
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-left">
                            <div class="result-name">Calculus Quiz #4</div>
                            <div class="result-meta">62 students attempted</div>
                        </div>
                        <div class="result-right">
                            <span class="result-avg">Avg: 84</span>
                            <span class="result-pass">55 passed</span>
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-left">
                            <div class="result-name">Linear Algebra Quiz #1</div>
                            <div class="result-meta">27 students attempted</div>
                        </div>
                        <div class="result-right">
                            <span class="result-avg">Avg: 65</span>
                            <span class="result-pass">19 passed</span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Pending Comments ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Pending Comments</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="comments-list">

                    <div class="comment-item">
                        <div class="comment-avatar">SA</div>
                        <div class="comment-body">
                            <div class="comment-course">Advanced Mathematics</div>
                            <div class="comment-text">Can someone explain the difference between eigenvalues and eigenvectors?</div>
                            <div class="comment-date">1 hour ago</div>
                        </div>
                    </div>

                    <div class="comment-item">
                        <div class="comment-avatar">MB</div>
                        <div class="comment-body">
                            <div class="comment-course">Statistics & Probability</div>
                            <div class="comment-text">I'm confused about the normal distribution formula in chapter 3.</div>
                            <div class="comment-date">3 hours ago</div>
                        </div>
                    </div>

                    <div class="comment-item">
                        <div class="comment-avatar">FZ</div>
                        <div class="comment-body">
                            <div class="comment-course">Linear Algebra</div>
                            <div class="comment-text">Is there extra material for the determinants chapter?</div>
                            <div class="comment-date">Yesterday</div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Announcements ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">My Announcements</h2>
                    <a href="#" class="dash-card-link">+ New →</a>
                </div>
                <div class="ann-list">

                    <div class="ann-item">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">Calculus Final moved to Friday</div>
                                <div class="ann-course">Advanced Mathematics</div>
                            </div>
                        </div>
                        <span class="ann-date">Today</span>
                    </div>

                    <div class="ann-item">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">New chapter uploaded: Bayes Theorem</div>
                                <div class="ann-course">Statistics & Probability</div>
                            </div>
                        </div>
                        <span class="ann-date">2 days ago</span>
                    </div>

                    <div class="ann-item">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">Formula sheet PDF added</div>
                                <div class="ann-course">Linear Algebra</div>
                            </div>
                        </div>
                        <span class="ann-date">4 days ago</span>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>
