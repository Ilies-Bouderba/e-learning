<div class="dash-layout">
    <livewire:course-sidebar :course="$course" active="quizzes" />

    <main class="dash-main">
        <div class="dash-header">
            <div>
                <div class="csh-dept">{{ $course->department->icon }} {{ $course->department->name }}</div>
                <h1 class="dash-title">{{ $course->title }} - Quizzes</h1>
                <p class="dash-subtitle">Manage your course quizzes</p>
            </div>
            <a href="{{ route('teacher.quizzes.create', $course) }}" class="btn btn-primary">+ Create New Quiz</a>
        </div>

        @if(session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        <div class="dash-card" style="margin-top: 0;">
            <div class="dash-card-header">
                <h2 class="dash-card-title">All Quizzes</h2>
                <span class="badge">{{ $quizzes->count() }} total</span>
            </div>

            @forelse($quizzes as $quiz)
            <div class="quiz-item" style="padding: 1.5rem; border-bottom: 1.5px solid rgba(15,14,23,0.08);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <span class="quiz-icon" style="font-size: 1.8rem;">📝</span>
                            <div>
                                <h3 class="quiz-title" style="font-family: var(--font-head); font-weight: 800; font-size: 1.1rem; margin: 0;">
                                    {{ $quiz->title }}
                                </h3>
                                <div class="quiz-meta" style="font-size: 0.75rem; color: var(--c-muted); margin-top: 0.25rem;">
                                    {{ $quiz->questions->count() }} questions • {{ $quiz->total_score }} points
                                </div>
                            </div>
                        </div>
                        <div class="quiz-status" style="margin-top: 0.5rem;">
                            @if($quiz->is_published)
                                <span class="badge" style="background: #10b981; color: white;">Published</span>
                            @else
                                <span class="badge" style="background: #6b7280; color: white;">Draft</span>
                            @endif
                            <span class="badge">{{ $quiz->attempts->count() }} attempts</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                        <a href="{{ route('teacher.quizzes.edit', ['course' => $course, 'quiz' => $quiz]) }}" class="btn-sm">Edit</a>
                        <a href="{{ route('quizzes.show', ['course' => $course, 'quiz' => $quiz]) }}" class="btn-sm">View Results</a>
                        <button class="btn-sm btn-{{ $quiz->is_published ? 'warning' : 'success' }}" wire:click="togglePublish({{ $quiz->id }})">
                            {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                        <button class="btn-sm btn-danger" wire:click="deleteQuiz({{ $quiz->id }})" wire:confirm="Delete this quiz?">Delete</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="mc-empty">
                <span>📝</span>
                <p>No quizzes created yet.</p>
                <a href="{{ route('quizzes.create', $course) }}" class="btn btn-primary" style="margin-top: 1rem;">Create Your First Quiz →</a>
            </div>
            @endforelse
        </div>
    </main>
</div>
