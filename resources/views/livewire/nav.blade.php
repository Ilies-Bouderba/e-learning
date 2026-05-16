<header class="nav-wrap">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">edu<span>me</span>x</a>

        <nav class="nav-links">
            @auth
                @if(auth()->user()->isTeacher())
                    <a href="{{ route('teacher.cours.index') }}" class="{{ request()->routeIs('teacher.cours.*') ? 'active' : '' }}">📚 My Courses</a>
                    <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                @elseif(auth()->user()->isStudent())
                    <a href="{{ route('student.cours.index') }}" class="{{ request()->routeIs('student.cours.*') ? 'active' : '' }}">📚 Browse Courses</a>
                    <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                @elseif(auth()->user()->isAdmin())
                    {{-- FIXED: changed from dashboard.admin to admin.dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">👑 Admin Panel</a>
                @endif
                <a href="#">ℹ️ About</a>
            @endauth

            @guest
                <a href="{{ route('home') }}">Home</a>
                <a href="#">Courses</a>
                <a href="#">About</a>
            @endguest
        </nav>

        <div class="nav-actions">
            @auth
                <div class="user-menu">
                    <button class="user-menu-btn" id="userMenuBtn">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role-badge {{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                            <div>
                                <div class="dropdown-name">{{ auth()->user()->name }}</div>
                                <div class="dropdown-email">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        @if(auth()->user()->isTeacher())
                            <a href="{{ route('teacher.dashboard') }}" class="dropdown-link"><span>📊</span> Dashboard</a>
                            <a href="{{ route('teacher.cours.index') }}" class="dropdown-link"><span>📚</span> My Courses</a>
                            <a href="{{ route('teacher.cours.create') }}" class="dropdown-link"><span>➕</span> Create Course</a>
                        @elseif(auth()->user()->isStudent())
                            <a href="{{ route('student.dashboard') }}" class="dropdown-link"><span>📊</span> Dashboard</a>
                            <a href="{{ route('student.cours.index') }}" class="dropdown-link"><span>📚</span> Browse Courses</a>
                        @elseif(auth()->user()->isAdmin())
                            {{-- FIXED: changed from dashboard.admin to admin.dashboard --}}
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-link"><span>👑</span> Admin Dashboard</a>
                            <a href="{{ route('admin.teachers') }}" class="dropdown-link"><span>👨‍🏫</span> Manage Teachers</a>
                            <a href="{{ route('admin.students') }}" class="dropdown-link"><span>🎓</span> Manage Students</a>
                            <a href="{{ route('admin.departments') }}" class="dropdown-link"><span>🏛️</span> Manage Departments</a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <button wire:click="logout" class="dropdown-link" style="width:100%; text-align:left; background:none; border:none;">
                            <span>🚪</span> Logout
                        </button>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
            @endauth
        </div>

        <button class="nav-burger" id="navBurger"><span></span><span></span><span></span></button>
    </div>

    <div class="nav-mobile" id="navMobile">
        @auth
            <div class="mobile-user-info">
                <div class="mobile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div>
                    <div class="mobile-user-name">{{ auth()->user()->name }}</div>
                    <div class="mobile-user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            <div class="mobile-divider"></div>
            @if(auth()->user()->isTeacher())
                <a href="{{ route('teacher.cours.index') }}">📚 My Courses</a>
                <a href="{{ route('teacher.dashboard') }}">📊 Dashboard</a>
                <a href="{{ route('teacher.cours.create') }}">➕ Create Course</a>
            @elseif(auth()->user()->isStudent())
                <a href="{{ route('student.cours.index') }}">📚 Browse Courses</a>
                <a href="{{ route('student.dashboard') }}">📊 Dashboard</a>
            @elseif(auth()->user()->isAdmin())
                {{-- FIXED: changed from dashboard.admin to admin.dashboard --}}
                <a href="{{ route('admin.dashboard') }}">👑 Admin Dashboard</a>
                <a href="{{ route('admin.teachers') }}">👨‍🏫 Teachers</a>
                <a href="{{ route('admin.students') }}">🎓 Students</a>
                <a href="{{ route('admin.departments') }}">🏛️ Departments</a>
            @endif
            <a href="#">ℹ️ About</a>
            <div class="mobile-divider"></div>
            <button wire:click="logout" class="btn btn-primary" style="width:100%;">Logout</button>
        @else
            <a href="{{ route('home') }}">Home</a>
            <a href="#">Courses</a>
            <a href="#">About</a>
            <div class="mobile-divider"></div>
            <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
        @endauth
    </div>
</header>

<script>
    document.getElementById('navBurger')?.addEventListener('click', () => {
        document.getElementById('navMobile')?.classList.toggle('open');
    });
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });
        document.addEventListener('click', (e) => {
            if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }
</script>

<style>
.user-menu {
    position: relative;
}
.user-menu-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius);
    transition: background 0.15s;
}
.user-menu-btn:hover {
    background: rgba(15,14,23,0.05);
}
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--c-dark);
    color: var(--c-yellow);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-head);
    font-weight: 800;
    font-size: 0.8rem;
}
.user-name {
    font-family: var(--font-head);
    font-size: 0.85rem;
    font-weight: 700;
}
.user-role-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 999px;
    font-weight: 700;
}
.user-role-badge.teacher {
    background: var(--c-yellow);
    color: var(--c-dark);
}
.user-role-badge.student {
    background: var(--c-dark);
    color: var(--c-bg);
}
.user-role-badge.admin {
    background: #ef4444;
    color: white;
}
.dropdown-arrow {
    font-size: 0.7rem;
    color: var(--c-muted);
}
.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 0.5rem;
    background: var(--c-bg);
    border: var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    min-width: 280px;
    z-index: 1000;
    display: none;
}
.user-dropdown.show {
    display: block;
}
.dropdown-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
}
.dropdown-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--c-dark);
    color: var(--c-yellow);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-head);
    font-weight: 800;
    font-size: 1rem;
}
.dropdown-name {
    font-family: var(--font-head);
    font-weight: 800;
    font-size: 0.9rem;
}
.dropdown-email {
    font-size: 0.75rem;
    color: var(--c-muted);
}
.dropdown-divider {
    height: 1px;
    background: rgba(15,14,23,0.1);
    margin: 0.5rem 0;
}
.dropdown-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    font-family: var(--font-head);
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--c-dark);
    transition: background 0.15s;
    text-decoration: none;
}
.dropdown-link:hover {
    background: rgba(255,225,77,0.2);
}
.nav-links a.active {
    color: var(--c-dark);
    font-weight: 800;
    position: relative;
}
.nav-links a.active::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--c-yellow);
}
</style>
