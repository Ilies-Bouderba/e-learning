<div class="dash-layout" style="grid-template-columns: 1fr;">
    <main class="dash-main">
        <div class="dash-header">
            <div>
                <h1 class="dash-title">All Announcements</h1>
                <p class="dash-subtitle">From all your enrolled courses</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="btn btn-ghost">← Back to Dashboard</a>
        </div>

        <div class="dash-card">
            @forelse($announcements as $ann)
                <div class="announcement-item" style="padding: 1.5rem; border-bottom: 1.5px solid rgba(15,14,23,0.08);">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <span class="ann-icon" style="font-size: 1.8rem;">📢</span>
                            <div>
                                <h3 style="font-family: var(--font-head); font-weight: 800; font-size: 1.1rem; margin: 0;">{{ $ann->title }}</h3>
                                <div class="ann-date" style="font-size: 0.75rem; color: var(--c-muted); margin-top: 0.25rem;">
                                    {{ $ann->course->title }} · Posted {{ $ann->posted_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <div style="font-size: 0.95rem; line-height: 1.6; margin-top: 0.5rem;">
                            {{ $ann->content }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="mc-empty">
                    <span>📭</span>
                    <p>No announcements yet from your enrolled courses.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
