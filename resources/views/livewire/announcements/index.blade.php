<div x-data="{ open: false, selected: null }">
    <div class="dash-layout">
        <livewire:course-sidebar :cour="$cour" active="announcements" />

        <main class="dash-main">
            <div class="dash-header">
                <div>
                    <div class="csh-dept">{{ $cour->department->icon }} {{ $cour->department->name }}</div>
                    <h1 class="dash-title">{{ $cour->title }}</h1>
                    <p class="dash-subtitle">Manage course announcements</p>
                </div>
                <a href="{{ route('announcements.create', $cour) }}" class="btn btn-primary">+ New Announcement</a>
            </div>

            @if (session('success'))
                <div class="mc-flash">{{ session('success') }}</div>
            @endif

            <div class="dash-card" style="margin-top: 0;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">All Announcements</h2>
                    <span class="badge">{{ $announcements->count() }} total</span>
                </div>

                @forelse($announcements as $ann)
                    <div class="announcement-item"
                        style="padding: 1.5rem; border-bottom: 1.5px solid rgba(15,14,23,0.08);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                                    <span class="ann-icon" style="font-size: 1.8rem;">📢</span>
                                    <div>
                                        <h3 class="ann-title"
                                            style="font-family: var(--font-head); font-weight: 800; font-size: 1.1rem; margin: 0; cursor: pointer; color: var(--c-dark);"
                                            @click="selected = {{ json_encode($ann) }}; open = true">
                                            {{ $ann->title }}
                                        </h3>
                                        <div class="ann-date"
                                            style="font-size: 0.75rem; color: var(--c-muted); margin-top: 0.25rem;">
                                            Posted {{ $ann->posted_at->diffForHumans() }} •
                                            {{ $ann->posted_at->format('F j, Y, g:i a') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="ann-content"
                                    style="font-size: 0.95rem; line-height: 1.6; color: var(--c-dark); margin-top: 0.5rem;">
                                    {{ Str::limit($ann->content, 150) }}
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                                <button class="mc-btn-delete" wire:click="delete({{ $ann->id }})"
                                    wire:confirm="Are you sure you want to delete this announcement?">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="mc-empty">
                        <span>📭</span>
                        <p>No announcements yet.</p>
                        <a href="{{ route('announcements.create', $cour) }}" class="btn btn-primary"
                            style="margin-top: 1rem;">Post Your First Announcement →</a>
                    </div>
                @endforelse
            </div>

            <div class="dash-card" style="margin-top: 1.5rem;">
                <a href="{{ route('cours.show', $cour) }}" class="btn btn-ghost">← Back to Course</a>
            </div>
        </main>
    </div>

    <div x-show="open"
        style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000;"
        x-cloak>
        <div
            style="background: var(--c-bg); border: var(--border); border-radius: var(--radius); max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; padding: 2rem;">
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
