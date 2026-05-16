<div class="dash-layout">
    <livewire:course-sidebar :cour="$cour" active="exams" />

    <main class="dash-main">
        <div class="dash-header">
            <div>
                <div class="csh-dept">{{ $cour->department->icon }} {{ $cour->department->name }}</div>
                <h1 class="dash-title">{{ $cour->title }} - Exams</h1>
                <p class="dash-subtitle">Manage your course exams</p>
            </div>
            <a href="{{ route('teacher.exams.create', $cour) }}" class="btn btn-primary">+ Create New Exam</a>
        </div>

        @if(session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        <div class="dash-card" style="margin-top: 0;">
            <div class="dash-card-header">
                <h2 class="dash-card-title">All Exams</h2>
                <span class="badge">{{ $exams->count() }} total</span>
            </div>

            @forelse($exams as $exam)
            <div class="exam-item" style="padding: 1.5rem; border-bottom: 1.5px solid rgba(15,14,23,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <span class="exam-icon" style="font-size: 1.8rem;">📝</span>
                            <div>
                                <h3 class="exam-title" style="font-family: var(--font-head); font-weight: 800; font-size: 1.1rem; margin: 0;">
                                    {{ $exam->title }}
                                </h3>
                                <div class="exam-meta" style="font-size: 0.75rem; color: var(--c-muted); margin-top: 0.25rem;">
                                    {{ $exam->questions->count() }} questions • {{ $exam->total_score }} points
                                    @if($exam->duration_minutes) • ⏱️ {{ $exam->duration_minutes }} minutes @endif
                                </div>
                            </div>
                        </div>
                        <div class="exam-status" style="margin-top: 0.5rem;">
                            @if($exam->is_published)
                                <span class="badge" style="background: #10b981; color: white;">Published</span>
                            @else
                                <span class="badge" style="background: #6b7280; color: white;">Draft</span>
                            @endif
                            <span class="badge">{{ $exam->attempts->count() }} attempts</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                        <a href="{{ route('exams.show', ['cour' => $cour, 'exam' => $exam]) }}" class="btn-sm">View Results</a>
                        <a href="{{ route('teacher.exams.edit', ['cour' => $cour, 'exam' => $exam]) }}" class="btn-sm">Edit</a>
                        <button class="btn-sm btn-{{ $exam->is_published ? 'warning' : 'success' }}" wire:click="togglePublish({{ $exam->id }})">
                            {{ $exam->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                        <button class="btn-sm btn-danger" wire:click="deleteExam({{ $exam->id }})" wire:confirm="Delete this exam?">Delete</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="mc-empty">
                <span>📝</span>
                <p>No exams created yet.</p>
                <a href="{{ route('teacher.exams.create', $cour) }}" class="btn btn-primary" style="margin-top: 1rem;">Create Your First Exam →</a>
            </div>
            @endforelse
        </div>
    </main>
</div>
