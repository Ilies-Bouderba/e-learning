<aside class="sidebar">
    <a href="{{ route('home') }}" class="sidebar-logo">edu<span>me</span>x</a>
    <nav class="sidebar-nav">
        <span class="sidebar-nav-label">Course</span>
        <a href="{{ route('cours.show', $cour) }}" class="sidebar-link {{ $active == 'chapters' ? 'active' : '' }}">
            <span class="sidebar-icon">📖</span> Chapters
        </a>
        <a href="{{ route('announcements.index', $cour) }}"
            class="sidebar-link {{ $active == 'announcements' ? 'active' : '' }}">
            <span class="sidebar-icon">📢</span> Announcements
        </a>
        <a href="{{ route('exams.index', $cour) }}" class="sidebar-link {{ $active == 'exams' ? 'active' : '' }}">
            <span class="sidebar-icon">📝</span> Exams
        </a>

        @if (auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('chapters.create', $cour) }}" class="sidebar-link">
                <span class="sidebar-icon">➕</span> Add Chapter
            </a>
            <a href="{{ route('announcements.create', $cour) }}" class="sidebar-link">
                <span class="sidebar-icon">📣</span> Post Announcement
            </a>
            <a href="{{ route('exams.create', $cour) }}" class="sidebar-link">
                <span class="sidebar-icon">✍️</span> Create Exam
            </a>
            <a href="{{ route('cours.edit', $cour) }}" class="sidebar-link {{ $active == 'edit' ? 'active' : '' }}">
                <span class="sidebar-icon">✏️</span> Edit Course
            </a>
        @endif
    </nav>

</aside>
