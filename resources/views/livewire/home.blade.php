{{-- resources/views/livewire/home.blade.php --}}

<section>
    {{-- ========== HERO ========== --}}
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-left">
                    <span class="badge">📚 Learning Platform</span>
                    <h1 class="hero-title">
                        Learn.<br>
                        <span class="stroke-text">Grow.</span><br>
                        Succeed.
                    </h1>
                    <p class="hero-sub">
                        Join thousands of students in structured courses, live exams, and real-time progress tracking —
                        all in one place.
                    </p>
                    <div class="hero-actions">
                        <a href="#" class="btn btn-primary">Sign Up Free →</a>
                        <a href="#" class="btn btn-ghost">Browse Courses</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-num">120+</span>
                            <span class="stat-label">Courses</span>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat">
                            <span class="stat-num">3.4k</span>
                            <span class="stat-label">Students</span>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat">
                            <span class="stat-num">48</span>
                            <span class="stat-label">Teachers</span>
                        </div>
                    </div>
                </div>

                <div class="hero-right">
                    <div class="float-card card-course">
                        <div class="card-course-top">
                            <span class="course-icon">🧬</span>
                            <span class="card-tag">In Progress</span>
                        </div>
                        <div class="course-name">Biology Molecular</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 72%"></div>
                        </div>
                        <div class="progress-label">72% complete · 5 chapters left</div>
                    </div>

                    <div class="float-card card-exam">
                        <div class="exam-label">📝 Upcoming Exam</div>
                        <div class="exam-title">Chemistry Mid-Term</div>
                        <div class="exam-meta">In 3 days · 90 min · 100 pts</div>
                    </div>

                    <div class="float-card card-score">
                        <span class="score-emoji">🏆</span>
                        <div>
                            <div class="score-num">94</div>
                            <div class="score-label">Last score</div>
                        </div>
                    </div>

                    <div class="deco-circle"></div>
                    <div class="deco-dot"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== MARQUEE TICKER ========== --}}
    <div class="ticker-wrap">
        <div class="ticker-track">
            <span class="ticker-item">Courses <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Exams <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Quizzes <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Chapters <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Announcements <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Progress Tracking <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Live Comments <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Attachments <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Courses <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Exams <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Quizzes <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Chapters <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Announcements <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Progress Tracking <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Live Comments <span class="ticker-dot">✦</span></span>
            <span class="ticker-item">Attachments <span class="ticker-dot">✦</span></span>
        </div>
    </div>

    {{-- ========== FEATURED COURSES ========== --}}
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <span class="section-tag">Ready to learn</span>
                    <h2 class="section-title">Featured Courses</h2>
                </div>
                <a href="#" class="btn btn-outline">All Courses →</a>
            </div>

            <div>
                <livewire:cours-card />
            </div>
    </section>

    {{-- ========== HOW IT WORKS ========== --}}
    <section class="section section-dark">
        <div class="container">
            <div class="section-header">
                <div>
                    <span class="section-tag tag-yellow">How it works</span>
                    <h2 class="section-title light">From zero to certified.</h2>
                </div>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">01</div>
                    <div class="step-icon">🎓</div>
                    <h3>Enroll in a Course</h3>
                    <p>Pick from our library of courses taught by expert teachers.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">02</div>
                    <div class="step-icon">📖</div>
                    <h3>Study Chapters</h3>
                    <p>Go through structured chapters with attachments and quizzes.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">03</div>
                    <div class="step-icon">📝</div>
                    <h3>Take Exams</h3>
                    <p>Test your knowledge with timed exams and instant scoring.</p>
                </div>
                <div class="step-card step-card-accent">
                    <div class="step-num">04</div>
                    <div class="step-icon">🏆</div>
                    <h3>Track Progress</h3>
                    <p>Monitor your completion and scores across all your courses.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== ANNOUNCEMENTS ========== --}}
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <span class="section-tag">Stay updated</span>
                    <h2 class="section-title">Latest Announcements</h2>
                </div>
            </div>
            <div class="announcements-list">
                <div class="announcement-item">
                    <div class="ann-left">
                        <span class="ann-icon">📢</span>
                        <div>
                            <div class="ann-title">Assignment deadline extended</div>
                            <div class="ann-course">Biology Molecular</div>
                        </div>
                    </div>
                    <span class="ann-date">2 hours ago</span>
                </div>
                <div class="announcement-item">
                    <div class="ann-left">
                        <span class="ann-icon">📢</span>
                        <div>
                            <div class="ann-title">New chapter uploaded: Derivatives</div>
                            <div class="ann-course">Advanced Mathematics</div>
                        </div>
                    </div>
                    <span class="ann-date">Yesterday</span>
                </div>
                <div class="announcement-item">
                    <div class="ann-left">
                        <span class="ann-icon">📢</span>
                        <div>
                            <div class="ann-title">Mid-term exam date confirmed</div>
                            <div class="ann-course">Organic Chemistry</div>
                        </div>
                    </div>
                    <span class="ann-date">3 days ago</span>
                </div>
                <div class="announcement-item">
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
    </section>

    {{-- ========== CTA BANNER ========== --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-inner">
                <div class="cta-text">
                    <h2>Ready to start learning?</h2>
                    <p>Join now and access every course, exam, and resource — free to start.</p>
                </div>
                <div class="cta-actions">
                    <a href="#" class="btn btn-primary btn-lg">Get Started Free →</a>
                    <a href="#" class="btn btn-ghost-dark">Already have an account?</a>
                </div>
                <div class="cta-deco">✦</div>
            </div>
        </div>
    </section>
</section>
