<div class="dash-layout">
    <livewire:course-sidebar :cour="$cour" active="chapters" />

    <main class="dash-main">
        <div class="course-show-header">
            <div class="csh-left">
                <div class="csh-icon">{{ $cour->icon }}</div>
                <div>
                    <div class="csh-dept">{{ $cour->department->icon }} {{ $cour->department->name }}</div>
                    <h1 class="csh-title">{{ $chapter->title }}</h1>
                    <p class="csh-desc">Chapter {{ $chapter->chapter_number }} of {{ $cour->title }}</p>
                </div>
            </div>
            @if (auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                <div class="csh-actions">
                    <a href="{{ route('chapters.edit', ['cour' => $cour, 'chapter' => $chapter]) }}"
                        class="btn btn-primary">Edit Chapter</a>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        <div class="dash-card dash-card-wide">
            <div class="chapter-content" style="padding: 1rem 0; line-height: 1.8;">
                {!! nl2br(e($chapter->content)) !!}
            </div>

            @if ($chapter->attachments && $chapter->attachments->count() > 0)
                <div class="dash-card-header" style="margin-top: 2rem;">
                    <h2 class="dash-card-title">📎 Attachments</h2>
                </div>
                <div class="attach-list">
                    @foreach ($chapter->attachments as $attachment)
                        <div class="attach-item">
                            <div class="attach-type attach-{{ $attachment->type }}">
                                {{ strtoupper($attachment->type) }}
                            </div>
                            <div class="attach-info">
                                <div class="attach-name">{{ $attachment->title }}</div>
                                <div class="attach-course" style="font-size: 0.7rem;">Uploaded
                                    {{ $attachment->created_at->diffForHumans() }}</div>
                            </div>
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" class="attach-dl"
                                target="_blank" download>↓</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="dash-card" style="margin-top: 1.5rem;">
            <a href="{{ route('cours.show', $cour) }}" class="btn btn-ghost">← Back to Course</a>
        </div>
    </main>
</div>
