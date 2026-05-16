<div class="dash-layout">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="sidebar-logo">edu<span>me</span>x</a>
        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><span class="sidebar-icon">🏠</span>
                Dashboard</a>
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('admin.teachers') }}" class="sidebar-link"><span class="sidebar-icon">👨‍🏫</span>
                Teachers</a>
            <a href="{{ route('admin.students') }}" class="sidebar-link"><span class="sidebar-icon">🎓</span>
                Students</a>
            <a href="{{ route('admin.departments') }}" class="sidebar-link"><span class="sidebar-icon">🏛️</span>
                Departments</a>
        </nav>
        <div class="sidebar-user">
            <div class="sidebar-avatar">AD</div>
            <div class="sidebar-user-info"><span class="sidebar-user-name">{{ auth()->user()->name }}</span><span
                    class="sidebar-user-role">Admin</span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sidebar-logout"
                    title="Logout">↩</button></form>
        </div>
    </aside>
    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Admin Dashboard 👑</h1>
                <p class="dash-subtitle">Platform overview and user management.</p>
            </div>
        </div>
        <div class="dash-stats">
            <div class="dash-stat-card dash-stat-yellow">
                <div class="dsc-icon">👨‍🏫</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalTeachers }}</span><span
                        class="dsc-label">Teachers</span></div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">🎓</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalStudents }}</span><span
                        class="dsc-label">Students</span></div>
            </div>
            <div class="dash-stat-card">
                <div class="dsc-icon">🏛️</div>
                <div class="dsc-info"><span class="dsc-num">{{ $totalDepts }}</span><span
                        class="dsc-label">Departments</span></div>
            </div>
        </div>
        <div class="dash-grid">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Departments</h2><a href="{{ route('admin.departments') }}"
                        class="dash-card-link">Manage →</a>
                </div>
                <div class="dept-grid">
                    @foreach ($departments as $dept)
                        <div class="dept-item"><span class="dept-icon">{{ $dept->icon }}</span>
                            <div class="dept-info">
                                <div class="dept-name">{{ $dept->name }}</div>
                                <div class="dept-count">{{ $dept->courses_count }} courses</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Teachers</h2><a href="{{ route('admin.teachers') }}"
                        class="dash-card-link">View all →</a>
                </div>
                <div class="students-list">
                    @foreach ($recentTeachers as $t)
                        <div class="student-item">
                            <div class="student-avatar sidebar-avatar-teacher">{{ strtoupper(substr($t->name, 0, 2)) }}
                            </div>
                            <div class="student-info">
                                <div class="student-name">{{ $t->name }}</div>
                                <div class="student-course">{{ $t->courses_count }} courses · {{ $t->email }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Recent Students</h2><a href="{{ route('admin.students') }}"
                        class="dash-card-link">View all →</a>
                </div>
                <div class="students-list">
                    @foreach ($recentStudents as $s)
                        <div class="student-item">
                            <div class="student-avatar">{{ strtoupper(substr($s->name, 0, 2)) }}</div>
                            <div class="student-info">
                                <div class="student-name">{{ $s->name }}</div>
                                <div class="student-course">{{ $s->enrollments_count }} enrollments ·
                                    {{ $s->email }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>
