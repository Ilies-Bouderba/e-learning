<aside class="sidebar">
    <a href="{{ route('home') }}" class="sidebar-logo">edu<span>me</span>x</a>
    <nav class="sidebar-nav">
        <span class="sidebar-nav-label">Course</span>
        <a href="{{ route('cours.show', $course) }}" class="sidebar-link {{ $active == 'chapters' ? 'active' : '' }}">
            <span class="sidebar-icon">📖</span> Chapters
        </a>
        <a href="{{ route('announcements.index', $course) }}" class="sidebar-link {{ $active == 'announcements' ? 'active' : '' }}">
            <span class="sidebar-icon">📢</span> Announcements
        </a>

        @if(auth()->user()->isTeacher())
            <a href="{{ route('teacher.quizzes.index', $course) }}" class="sidebar-link {{ $active == 'quizzes' ? 'active' : '' }}">
                <span class="sidebar-icon">📝</span> Quizzes
            </a>
        @elseif(auth()->user()->isStudent())
            <a href="{{ route('student.quizzes.index', $course) }}" class="sidebar-link {{ $active == 'quizzes' ? 'active' : '' }}">
                <span class="sidebar-icon">📝</span> Quizzes
            </a>
        @endif

        @if(auth()->user()->isTeacher() && (int) $course->teacher_id === (int) auth()->id())
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('teacher.chapters.create', $course) }}" class="sidebar-link">
                <span class="sidebar-icon">➕</span> Add Chapter
            </a>
            <a href="{{ route('teacher.announcements.create', $course) }}" class="sidebar-link">
                <span class="sidebar-icon">📣</span> Post Announcement
            </a>
            <a href="{{ route('teacher.quizzes.create', $course) }}" class="sidebar-link">
                <span class="sidebar-icon">✍️</span> Create Quiz
            </a>
            <a href="{{ route('teacher.exams.create', $course) }}" class="sidebar-link">
                <span class="sidebar-icon">📝</span> Create Exam
            </a>
            <a href="{{ route('teacher.cours.edit', $course) }}" class="sidebar-link {{ $active == 'edit' ? 'active' : '' }}">
                <span class="sidebar-icon">✏️</span> Edit Course
            </a>
        @endif
    </nav>
</aside>
