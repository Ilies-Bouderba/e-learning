<header class="nav-wrap">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
            edu<span>me</span>x
        </a>

        <nav class="nav-links">
            @auth
                @if (auth()->user()->isTeacher() || auth()->user()->isStudent())
                    <a href="{{ route('cours.index') }}" class="{{ request()->routeIs('cours.*') ? 'active' : '' }}">
                        📚 Courses
                    </a>
                @endif

                @if (auth()->user()->isTeacher())
                    <a href="{{ route('dashboard.teacher') }}"
                        class="{{ request()->routeIs('dashboard.teacher') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                @elseif(auth()->user()->isStudent())
                    <a href="{{ route('dashboard.student') }}"
                        class="{{ request()->routeIs('dashboard.student') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                @elseif(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard.admin') }}"
                        class="{{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                        👑 Admin Panel
                    </a>
                @endif

                <a href="#" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                    ℹ️ About
                </a>
            @endauth

            @guest
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="#">Courses</a>
                <a href="#">About</a>
            @endguest
        </nav>

        <div class="nav-actions">
            @auth
                <div class="user-menu">
                    <button class="user-menu-btn" id="userMenuBtn">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role-badge {{ auth()->user()->role }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                        <span class="dropdown-arrow">▼</span>
                    </button>

                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="dropdown-name">{{ auth()->user()->name }}</div>
                                <div class="dropdown-email">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>

                        @if (auth()->user()->isTeacher())
                            <a href="{{ route('dashboard.teacher') }}" class="dropdown-link">
                                <span>📊</span> Dashboard
                            </a>
                            <a href="{{ route('cours.index') }}" class="dropdown-link">
                                <span>📚</span> My Courses
                            </a>
                            <a href="{{ route('cours.create') }}" class="dropdown-link">
                                <span>➕</span> Create Course
                            </a>
                        @elseif(auth()->user()->isStudent())
                            <a href="{{ route('dashboard.student') }}" class="dropdown-link">
                                <span>📊</span> Dashboard
                            </a>
                            <a href="{{ route('cours.index') }}" class="dropdown-link">
                                <span>📚</span> Browse Courses
                            </a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('dashboard.admin') }}" class="dropdown-link">
                                <span>👑</span> Admin Dashboard
                            </a>
                            <a href="{{ route('admin.teachers') }}" class="dropdown-link">
                                <span>👨‍🏫</span> Manage Teachers
                            </a>
                            <a href="{{ route('admin.students') }}" class="dropdown-link">
                                <span>🎓</span> Manage Students
                            </a>
                            <a href="{{ route('admin.departments') }}" class="dropdown-link">
                                <span>🏛️</span> Manage Departments
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-link" wire:click="logout" wire:prevent>
                            <span>🚪</span> Logout
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
            @endauth
        </div>

        <button class="nav-burger" id="navBurger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
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

            @if (auth()->user()->isTeacher() || auth()->user()->isStudent())
                <a href="{{ route('cours.index') }}">📚 Courses</a>
            @endif

            @if (auth()->user()->isTeacher())
                <a href="{{ route('dashboard.teacher') }}">📊 Dashboard</a>
                <a href="{{ route('cours.create') }}">➕ Create Course</a>
            @elseif(auth()->user()->isStudent())
                <a href="{{ route('dashboard.student') }}">📊 Dashboard</a>
            @elseif(auth()->user()->isAdmin())
                <a href="{{ route('dashboard.admin') }}">👑 Admin Panel</a>
                <a href="{{ route('admin.teachers') }}">👨‍🏫 Teachers</a>
                <a href="{{ route('admin.students') }}">🎓 Students</a>
                <a href="{{ route('admin.departments') }}">🏛️ Departments</a>
            @endif

            <a href="#">ℹ️ About</a>
            <div class="mobile-divider"></div>
            <button class="btn btn-primary" wire:click="logout" style="margin-top: 0.5rem;">Logout</button>
        @else
            <a href="{{ route('home') }}">Home</a>
            <a href="#">Courses</a>
            <a href="#">About</a>
            <div class="mobile-divider"></div>
            <a href="{{ route('login') }}" class="btn btn-ghost" style="text-align: center;">Login</a>
        @endauth
    </div>
</header>

<script>
    document.getElementById('navBurger').addEventListener('click', () => {
        document.getElementById('navMobile').classList.toggle('open');
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
