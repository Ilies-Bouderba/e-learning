{{-- resources/views/livewire/dashboard/student.blade.php --}}

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
            <div class="sidebar-avatar">SA</div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">Sara Ahmed</span>
                <span class="sidebar-user-role">Student</span>
            </div>
            <a href="#" class="sidebar-logout" title="Logout">↩</a>
        </div>
    </aside>

    {{-- ========== MAIN CONTENT ========== --}}
    <main class="dash-main">

        {{-- Header --}}
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Good morning, Sara 👋</h1>
                <p class="dash-subtitle">Here's what's happening with your learning today.</p>
            </div>
            <a href="/courses" class="btn btn-primary">Browse Courses →</a>
        </div>

        {{-- Stats row --}}
        <div class="dash-stats">
            <div class="dash-stat-card">
                <div class="dsc-icon">📚</div>
                <div class="dsc-info">
                    <span class="dsc-num">4</span>
                    <span class="dsc-label">Enrolled Courses</span>
                </div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">✅</div>
                <div class="dsc-info">
                    <span class="dsc-num">12</span>
                    <span class="dsc-label">Chapters Done</span>
                </div>
            </div>
            <div class="dash-stat-card dash-stat-yellow">
                <div class="dsc-icon">🏆</div>
                <div class="dsc-info">
                    <span class="dsc-num">87</span>
                    <span class="dsc-label">Avg. Score</span>
                </div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">📝</div>
                <div class="dsc-info">
                    <span class="dsc-num">3</span>
                    <span class="dsc-label">Exams Taken</span>
                </div>
            </div>
        </div>

        <div class="dash-grid">

            {{-- ── Enrolled Courses ── --}}
            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">My Courses</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="enrolled-list">

                    <div class="enrolled-item">
                        <div class="enrolled-icon">🧬</div>
                        <div class="enrolled-info">
                            <div class="enrolled-name">Biology Molecular</div>
                            <div class="enrolled-meta">12 chapters · Sara Ahmed</div>
                            <div class="enrolled-bar">
                                <div class="enrolled-fill" style="width: 72%"></div>
                            </div>
                        </div>
                        <div class="enrolled-right">
                            <span class="enrolled-pct">72%</span>
                            <a href="#" class="btn-sm">Continue →</a>
                        </div>
                    </div>

                    <div class="enrolled-item">
                        <div class="enrolled-icon">📐</div>
                        <div class="enrolled-info">
                            <div class="enrolled-name">Advanced Mathematics</div>
                            <div class="enrolled-meta">9 chapters · Karim Messai</div>
                            <div class="enrolled-bar">
                                <div class="enrolled-fill" style="width: 45%"></div>
                            </div>
                        </div>
                        <div class="enrolled-right">
                            <span class="enrolled-pct">45%</span>
                            <a href="#" class="btn-sm">Continue →</a>
                        </div>
                    </div>

                    <div class="enrolled-item">
                        <div class="enrolled-icon">💻</div>
                        <div class="enrolled-info">
                            <div class="enrolled-name">Web Development</div>
                            <div class="enrolled-meta">15 chapters · Lina Bouali</div>
                            <div class="enrolled-bar">
                                <div class="enrolled-fill" style="width: 91%"></div>
                            </div>
                        </div>
                        <div class="enrolled-right">
                            <span class="enrolled-pct">91%</span>
                            <a href="#" class="btn-sm">Continue →</a>
                        </div>
                    </div>

                    <div class="enrolled-item">
                        <div class="enrolled-icon">⚗️</div>
                        <div class="enrolled-info">
                            <div class="enrolled-name">Organic Chemistry</div>
                            <div class="enrolled-meta">11 chapters · Yacine Brahimi</div>
                            <div class="enrolled-bar">
                                <div class="enrolled-fill" style="width: 20%"></div>
                            </div>
                        </div>
                        <div class="enrolled-right">
                            <span class="enrolled-pct">20%</span>
                            <a href="#" class="btn-sm">Continue →</a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Upcoming Exams ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Upcoming Exams</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="exam-list">

                    <div class="exam-item exam-soon">
                        <div class="exam-item-top">
                            <span class="exam-badge exam-badge-soon">In 2 days</span>
                            <span class="exam-pts">100 pts</span>
                        </div>
                        <div class="exam-item-name">Chemistry Mid-Term</div>
                        <div class="exam-item-meta">90 min · Organic Chemistry</div>
                    </div>

                    <div class="exam-item">
                        <div class="exam-item-top">
                            <span class="exam-badge">In 5 days</span>
                            <span class="exam-pts">80 pts</span>
                        </div>
                        <div class="exam-item-name">Calculus Final</div>
                        <div class="exam-item-meta">120 min · Advanced Mathematics</div>
                    </div>

                    <div class="exam-item">
                        <div class="exam-item-top">
                            <span class="exam-badge">In 2 weeks</span>
                            <span class="exam-pts">60 pts</span>
                        </div>
                        <div class="exam-item-name">Biology Quiz #3</div>
                        <div class="exam-item-meta">45 min · Biology Molecular</div>
                    </div>

                </div>
            </div>

            {{-- ── Recent Scores ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Scores</h2>
                </div>
                <div class="scores-list">

                    <div class="score-item">
                        <div class="score-left">
                            <div class="score-name">Web Dev Quiz #2</div>
                            <div class="score-course">Web Development</div>
                        </div>
                        <div class="score-badge score-high">94</div>
                    </div>

                    <div class="score-item">
                        <div class="score-left">
                            <div class="score-name">Biology Quiz #2</div>
                            <div class="score-course">Biology Molecular</div>
                        </div>
                        <div class="score-badge score-mid">76</div>
                    </div>

                    <div class="score-item">
                        <div class="score-left">
                            <div class="score-name">Math Mid-Term</div>
                            <div class="score-course">Advanced Mathematics</div>
                        </div>
                        <div class="score-badge score-high">88</div>
                    </div>

                    <div class="score-item">
                        <div class="score-left">
                            <div class="score-name">Web Dev Quiz #1</div>
                            <div class="score-course">Web Development</div>
                        </div>
                        <div class="score-badge score-low">61</div>
                    </div>

                </div>
            </div>

            {{-- ── Announcements ── --}}
            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Latest Announcements</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="ann-list">

                    <div class="ann-item">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">Assignment deadline extended</div>
                                <div class="ann-course">Biology Molecular</div>
                            </div>
                        </div>
                        <span class="ann-date">2 hours ago</span>
                    </div>

                    <div class="ann-item">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">New chapter uploaded: Derivatives</div>
                                <div class="ann-course">Advanced Mathematics</div>
                            </div>
                        </div>
                        <span class="ann-date">Yesterday</span>
                    </div>

                    <div class="ann-item">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">Mid-term exam date confirmed</div>
                                <div class="ann-course">Organic Chemistry</div>
                            </div>
                        </div>
                        <span class="ann-date">3 days ago</span>
                    </div>

                    <div class="ann-item">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">Lecture notes PDF now available</div>
                                <div class="ann-course">Web Development</div>
                            </div>
                        </div>
                        <span class="ann-date">1 week ago</span>
                    </div>

                </div>
            </div>

            {{-- ── Recent Comments ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">My Comments</h2>
                    <a href="#" class="dash-card-link">View all →</a>
                </div>
                <div class="comments-list">

                    <div class="comment-item">
                        <div class="comment-avatar">SA</div>
                        <div class="comment-body">
                            <div class="comment-course">Biology Molecular</div>
                            <div class="comment-text">Can someone explain the difference between mitosis and meiosis again?</div>
                            <div class="comment-date">2 days ago</div>
                        </div>
                    </div>

                    <div class="comment-item">
                        <div class="comment-avatar">SA</div>
                        <div class="comment-body">
                            <div class="comment-course">Web Development</div>
                            <div class="comment-text">The Flexbox chapter was really helpful, thanks!</div>
                            <div class="comment-date">5 days ago</div>
                        </div>
                    </div>

                    <div class="comment-item">
                        <div class="comment-avatar">SA</div>
                        <div class="comment-body">
                            <div class="comment-course">Advanced Mathematics</div>
                            <div class="comment-text">I'm stuck on problem 3 in chapter 4, any hints?</div>
                            <div class="comment-date">1 week ago</div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Attachments / Resources ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Resources</h2>
                </div>
                <div class="attach-list">

                    <div class="attach-item">
                        <div class="attach-type attach-pdf">PDF</div>
                        <div class="attach-info">
                            <div class="attach-name">Lecture Notes Ch.4</div>
                            <div class="attach-course">Web Development</div>
                        </div>
                        <a href="#" class="attach-dl">↓</a>
                    </div>

                    <div class="attach-item">
                        <div class="attach-type attach-doc">DOC</div>
                        <div class="attach-info">
                            <div class="attach-name">Biology Study Guide</div>
                            <div class="attach-course">Biology Molecular</div>
                        </div>
                        <a href="#" class="attach-dl">↓</a>
                    </div>

                    <div class="attach-item">
                        <div class="attach-type attach-pdf">PDF</div>
                        <div class="attach-info">
                            <div class="attach-name">Calculus Formula Sheet</div>
                            <div class="attach-course">Advanced Mathematics</div>
                        </div>
                        <a href="#" class="attach-dl">↓</a>
                    </div>

                    <div class="attach-item">
                        <div class="attach-type attach-vid">VID</div>
                        <div class="attach-info">
                            <div class="attach-name">Intro to Reactions</div>
                            <div class="attach-course">Organic Chemistry</div>
                        </div>
                        <a href="#" class="attach-dl">↓</a>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>
