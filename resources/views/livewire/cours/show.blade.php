<div x-data="{ open: false, selected: null }">
    <div class="dash-layout">
        <livewire:course-sidebar :cour="$cour" active="chapters" />

        <main class="dash-main">
            <div class="course-show-header">
                <div class="csh-left">
                    <div class="csh-icon">{{ $cour->icon }}</div>
                    <div>
                        <div class="csh-dept">{{ $cour->department->icon }} {{ $cour->department->name }}</div>
                        <h1 class="csh-title">{{ $cour->title }}</h1>
                        <p class="csh-desc">{{ $cour->description }}</p>
                        <div class="csh-meta">
                            <span>👨‍🏫 {{ $cour->teacher->name }}</span>
                            <span>📖 {{ $cour->chapters->count() }} chapters</span>
                            <span>📝 {{ $cour->exams->count() }} exams</span>
                            @if($cour->hasPassword())<span>🔒 Password protected</span>@endif
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mc-flash">{{ session('success') }}</div>
            @endif

            <!-- Student Progress Section -->
            @if(auth()->user()->isStudent())
                <div class="progress-section" style="margin-bottom: 2rem;">
                    <div class="dash-card">
                        <div class="dash-card-header">
                            <h2 class="dash-card-title">Your Progress</h2>
                            @php
                                $totalChapters = $cour->chapters->count();
                                $completedChapters = \App\Models\StudentProgress::where('student_id', auth()->id())
                                    ->whereIn('chapter_id', $cour->chapters->pluck('id'))
                                    ->where('completed', true)
                                    ->count();
                                $progressPercent = $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0;
                            @endphp
                            <span class="badge">{{ $completedChapters }}/{{ $totalChapters }} chapters</span>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                <span>Overall Course Progress</span>
                                <span>{{ $progressPercent }}%</span>
                            </div>
                            <div style="background: rgba(15,14,23,0.1); height: 10px; border-radius: 5px; overflow: hidden;">
                                <div style="width: {{ $progressPercent }}%; background: var(--c-yellow); height: 100%; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Chapters section -->
            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Chapters</h2>
                    @if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                        <a href="{{ route('teacher.chapters.create', $cour) }}" class="dash-card-link">+ Add Chapter</a>
                    @endif
                </div>
                @forelse($cour->chapters as $chapter)
                    @php
                        $isCompleted = false;
                        if(auth()->user()->isStudent()) {
                            $isCompleted = \App\Models\StudentProgress::where('student_id', auth()->id())
                                ->where('chapter_id', $chapter->id)
                                ->where('completed', true)
                                ->exists();
                        }
                    @endphp
                    <div class="chapter-item">
                        <div class="chapter-num">{{ str_pad($chapter->chapter_number, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="chapter-info">
                            <div class="chapter-title" style="{{ $isCompleted ? 'text-decoration: line-through; color: var(--c-muted);' : '' }}">
                                {{ $chapter->title }}
                            </div>
                            @if($chapter->content)<div class="chapter-content">{{ Str::limit($chapter->content, 120) }}</div>@endif
                        </div>
                        <div class="chapter-actions">
                            @if(auth()->user()->isStudent())
                                <input type="checkbox"
                                       wire:click="toggleChapter({{ $chapter->id }})"
                                       @if($isCompleted) checked @endif
                                       style="width: 18px; height: 18px; cursor: pointer; margin-right: 0.5rem;">
                            @endif
                            <a href="{{ route('chapters.show', ['cour' => $cour, 'chapter' => $chapter]) }}" class="btn-sm">View →</a>
                            @if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                                <a href="{{ route('teacher.chapters.edit', ['cour' => $cour, 'chapter' => $chapter]) }}" class="btn-sm">Edit</a>
                                <button class="btn-sm btn-danger" wire:click="deleteChapter({{ $chapter->id }})" wire:confirm="Delete this chapter?">Delete</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="empty-msg" style="padding: 1rem 0;">No chapters yet.</p>
                @endforelse
            </div>

            <!-- Quizzes section -->
            <div class="dash-card" style="margin-top: 2rem;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Quizzes</h2>
                    @if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                        <a href="{{ route('teacher.quizzes.create', $cour) }}" class="dash-card-link">+ Create Quiz</a>
                    @endif
                </div>
                <div class="quiz-list">
                    @php
                        $quizzes = $cour->quizzes()->where('is_published', true)->latest()->take(3)->get();
                    @endphp
                    @forelse($quizzes as $quiz)
                        @php
                            $attempt = null;
                            if(auth()->user()->isStudent()) {
                                $attempt = \App\Models\QuizAttempt::where('student_id', auth()->id())
                                    ->where('quiz_id', $quiz->id)
                                    ->first();
                            }
                            $status = $attempt ? ($attempt->completed_at ? 'completed' : 'in_progress') : 'not_started';
                        @endphp
                        <div class="quiz-item" style="padding: 1rem; border-bottom: 1px solid rgba(15,14,23,0.08); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div class="quiz-title" style="font-weight: 700;">{{ $quiz->title }}</div>
                                <div style="font-size: 0.75rem; color: var(--c-muted);">{{ $quiz->questions->count() }} questions</div>
                                @if(auth()->user()->isStudent() && $status == 'completed')
                                    <div style="font-size: 0.7rem; color: #10b981; margin-top: 0.25rem;">Score: {{ round($attempt->score) }}%</div>
                                @elseif(auth()->user()->isStudent() && $status == 'in_progress')
                                    <div style="font-size: 0.7rem; color: #f59e0b; margin-top: 0.25rem;">In Progress</div>
                                @endif
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                @if(auth()->user()->isStudent())
                                    @if($status == 'completed')
                                        <a href="{{ route('quizzes.show', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn-sm">View Results</a>
                                    @elseif($status == 'in_progress')
                                        <a href="{{ route('student.quizzes.take', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn-sm btn-primary">Continue →</a>
                                    @else
                                        <a href="{{ route('student.quizzes.take', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn-sm btn-primary">Start →</a>
                                    @endif
                                @elseif(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                                    <a href="{{ route('quizzes.show', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn-sm">View</a>
                                    <a href="{{ route('teacher.quizzes.edit', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn-sm">Edit</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No quizzes available yet.</p>
                    @endforelse
                    @if($cour->quizzes()->where('is_published', true)->count() > 3)
                        <div style="margin-top: 1rem; text-align: center;">
                            <a href="{{ route('teacher.quizzes.index', $cour) }}" class="btn-sm">View all quizzes →</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Exams Section -->
            <div class="dash-card" style="margin-top: 2rem;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">📝 Exams</h2>
                    @php
                        $visibleExamsCount = 0;
                        if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id()) {
                            $visibleExamsCount = $cour->exams()->count();
                        } else {
                            $visibleExamsCount = $cour->exams()->where('is_published', true)
                                ->where(function($q) {
                                    $q->whereNull('end_date')
                                      ->orWhere('end_date', '>=', now());
                                })->count();
                        }
                    @endphp
                    @if($visibleExamsCount > 0)
                        @if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                            <a href="{{ route('teacher.exams.index', $cour) }}" class="dash-card-link">Manage Exams →</a>
                        @else
                            <a href="{{ route('exams.index', $cour) }}" class="dash-card-link">View all →</a>
                        @endif
                    @endif
                </div>
                <div class="exam-list">
                    @php
                        // Get published exams for students, all exams for teachers
                        if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id()) {
                            $exams = $cour->exams()->latest()->take(3)->get();
                        } else {
                            // For students, only show exams that are NOT closed (available or upcoming)
                            $exams = $cour->exams()
                                ->where('is_published', true)
                                ->where(function($q) {
                                    $q->whereNull('end_date')
                                      ->orWhere('end_date', '>=', now());
                                })
                                ->latest()
                                ->take(3)
                                ->get();
                        }
                    @endphp
                    @forelse($exams as $exam)
                        @php
                            $attempt = null;
                            $status = 'not_started';
                            $isAvailable = false;
                            $isClosed = false;
                            $isUpcoming = false;

                            if(auth()->user()->isStudent()) {
                                $attempt = \App\Models\ExamAttempt::where('student_id', auth()->id())
                                    ->where('exam_id', $exam->id)
                                    ->first();
                                $status = $attempt ? ($attempt->completed_at ? 'completed' : 'in_progress') : 'not_started';

                                // Check availability
                                $now = now();
                                if($exam->end_date && $now->gt($exam->end_date)) {
                                    $isClosed = true;
                                } elseif($exam->start_date && $now->lt($exam->start_date)) {
                                    $isUpcoming = true;
                                } else {
                                    $isAvailable = true;
                                }
                            }
                        @endphp
                        <div class="exam-item" style="padding: 1rem; border-bottom: 1px solid rgba(15,14,23,0.08); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div class="exam-title" style="font-weight: 700;">{{ $exam->title }}</div>
                                <div style="font-size: 0.75rem; color: var(--c-muted);">{{ $exam->questions->count() }} questions • {{ $exam->total_score }} points</div>
                                @if($exam->duration_minutes)
                                    <div style="font-size: 0.7rem; color: var(--c-muted);">⏱️ {{ $exam->duration_minutes }} minutes</div>
                                @endif
                                @if($isClosed)
                                    <div style="font-size: 0.7rem; color: #ef4444; margin-top: 0.25rem;">🔒 Closed</div>
                                @elseif($isUpcoming && $exam->start_date)
                                    <div style="font-size: 0.7rem; color: #f59e0b; margin-top: 0.25rem;">Starts {{ $exam->start_date->format('M j, g:i A') }}</div>
                                @endif
                                @if(auth()->user()->isStudent() && $status == 'completed')
                                    <div style="font-size: 0.7rem; color: #10b981; margin-top: 0.25rem;">Score: {{ $exam->total_score > 0 ? round(($attempt->total_score / $exam->total_score) * 100) : 0 }}%</div>
                                @elseif(auth()->user()->isStudent() && $status == 'in_progress')
                                    <div style="font-size: 0.7rem; color: #f59e0b; margin-top: 0.25rem;">In Progress</div>
                                @endif
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                @if(auth()->user()->isStudent())
                                    @if($isClosed)
                                        <span class="btn-sm" style="background: #e5e7eb; cursor: not-allowed;">Closed</span>
                                    @elseif($isUpcoming)
                                        <span class="btn-sm" style="background: #e5e7eb; cursor: not-allowed;">Coming Soon</span>
                                    @elseif($status == 'completed')
                                        <a href="{{ route('exams.show', ['cour' => $cour, 'exam' => $exam]) }}" class="btn-sm">View Results</a>
                                    @elseif($status == 'in_progress')
                                        <a href="{{ route('exams.take', ['cour' => $cour, 'exam' => $exam]) }}" class="btn-sm btn-primary">Continue →</a>
                                    @else
                                        <a href="{{ route('exams.take', ['cour' => $cour, 'exam' => $exam]) }}" class="btn-sm btn-primary">Start Exam →</a>
                                    @endif
                                @elseif(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                                    <a href="{{ route('exams.show', ['cour' => $cour, 'exam' => $exam]) }}" class="btn-sm">View Results</a>
                                    <a href="{{ route('teacher.exams.edit', ['cour' => $cour, 'exam' => $exam]) }}" class="btn-sm">Edit</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="empty-msg">No exams available yet.</p>
                        @if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                            <div style="margin-top: 1rem; text-align: center;">
                                <a href="{{ route('teacher.exams.create', $cour) }}" class="btn-sm btn-primary">Create your first exam →</a>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

            <!-- Announcements section -->
            <div class="dash-card" style="margin-top: 2rem;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Latest Announcements</h2>
                    @if(auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                        <a href="{{ route('teacher.announcements.create', $cour) }}" class="dash-card-link">+ Post</a>
                    @endif
                </div>
                <div class="ann-list">
                    @forelse($cour->announcements->sortByDesc('posted_at')->take(3) as $ann)
                    <div class="ann-item" style="cursor: pointer;" @click="selected = {{ json_encode($ann) }}; open = true">
                        <div class="ann-left">
                            <span class="ann-icon">📢</span>
                            <div>
                                <div class="ann-title">{{ $ann->title }}</div>
                                <div class="ann-course">{{ Str::limit($ann->content, 60) }}</div>
                            </div>
                        </div>
                        <span class="ann-date">{{ $ann->posted_at ? $ann->posted_at->diffForHumans() : 'Unknown date' }}</span>
                    </div>
                    @empty
                    <p class="empty-msg">No announcements yet.</p>
                    @endforelse
                    @if($cour->announcements->count() > 3)
                        <div style="margin-top: 1rem; text-align: center;">
                            <a href="{{ route('announcements.index', $cour) }}" class="btn-sm">View all →</a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for viewing announcement details -->
    <div x-show="open" class="modal-overlay" x-cloak>
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-family: var(--font-head); font-size: 1.25rem; font-weight: 800;" x-text="selected?.title"></h3>
                <button @click="open = false" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>
            <div class="ann-date" style="color: var(--c-muted); margin-bottom: 1rem;">
                <span x-text="selected ? new Date(selected.posted_at).toLocaleString() : ''"></span>
            </div>
            <div style="line-height: 1.6;" x-text="selected?.content"></div>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: var(--c-bg);
    border: var(--border);
    border-radius: var(--radius);
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    padding: 2rem;
}
[x-cloak] { display: none !important; }
</style>
