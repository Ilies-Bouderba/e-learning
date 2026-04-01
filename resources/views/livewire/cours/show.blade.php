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
                            @if ($cour->hasPassword())
                                <span>🔒 Password protected</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mc-flash">{{ session('success') }}</div>
            @endif

            <div class="dash-card dash-card-wide">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Chapters</h2>
                    @if (auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                        <a href="{{ route('chapters.create', $cour) }}" class="dash-card-link">+ Add Chapter</a>
                    @endif
                </div>
                @forelse($cour->chapters as $chapter)
                    <div class="chapter-item">
                        <div class="chapter-num">{{ str_pad($chapter->chapter_number, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="chapter-info">
                            <div class="chapter-title">{{ $chapter->title }}</div>
                            @if ($chapter->content)
                                <div class="chapter-content">{{ Str::limit($chapter->content, 120) }}</div>
                            @endif
                        </div>
                        <div class="chapter-actions">
                            <a href="{{ route('chapters.show', ['cour' => $cour, 'chapter' => $chapter]) }}"
                                class="btn-sm">View →</a>
                            @if (auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                                <a href="{{ route('chapters.edit', ['cour' => $cour, 'chapter' => $chapter]) }}"
                                    class="btn-sm">Edit</a>
                                <button class="btn-sm btn-danger" wire:click="deleteChapter({{ $chapter->id }})"
                                    wire:confirm="Delete this chapter?">Delete</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="empty-msg" style="padding: 1rem 0;">No chapters yet.</p>
                @endforelse
            </div>

            <div class="dash-card" style="margin-top: 2rem;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Latest Announcements</h2>
                    @if (auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                        <a href="{{ route('announcements.create', $cour) }}" class="dash-card-link">+ Post</a>
                    @endif
                </div>
                <div class="ann-list">
                    @forelse($cour->announcements->sortByDesc('posted_at')->take(3) as $ann)
                        <div class="ann-item" style="cursor: pointer;"
                            @click="selected = {{ json_encode($ann) }}; open = true">
                            <div class="ann-left">
                                <span class="ann-icon">📢</span>
                                <div>
                                    <div class="ann-title">{{ $ann->title }}</div>
                                    <div class="ann-course">{{ Str::limit($ann->content, 60) }}</div>
                                </div>
                            </div>
                            <span class="ann-date">{{ $ann->posted_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="empty-msg">No announcements yet.</p>
                    @endforelse
                    @if ($cour->announcements->count() > 3)
                        <div class="text-center mt-2">
                            <a href="{{ route('announcements.index', $cour) }}" class="btn-sm">View all →</a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <div x-show="open" class="modal-overlay" x-cloak>
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-family: var(--font-head); font-size: 1.25rem; font-weight: 800;"
                    x-text="selected?.title"></h3>
                <button @click="open = false"
                    style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>
            <div class="ann-date" style="color: var(--c-muted); margin-bottom: 1rem;">
                <span x-text="selected ? new Date(selected.posted_at).toLocaleString() : ''"></span>
            </div>
            <div style="line-height: 1.6;" x-text="selected?.content"></div>
        </div>
    </div>
</div>
