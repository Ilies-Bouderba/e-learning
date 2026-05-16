<div class="dash-layout" style="grid-template-columns: 1fr;">
    <main class="dash-main">
        <div class="course-show-header">
            <div class="csh-left">
                <div class="csh-icon">{{ $cour->icon }}</div>
                <div>
                    <div class="csh-dept">{{ $cour->department->icon }} {{ $cour->department->name }}</div>
                    <h1 class="csh-title">{{ $cour->title }} - Quizzes</h1>
                    <p class="csh-desc">Test your knowledge with these quizzes</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        <div class="dash-card" style="margin-top: 0;">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Available Quizzes</h2>
                <span class="badge">{{ $quizzes->count() }} total</span>
            </div>

            @forelse($quizzes as $quiz)
                @php
                    $status = $this->getQuizStatus($quiz->id);
                    $attempt = $this->attempts->get($quiz->id);
                @endphp
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
                            <div class="quiz-description" style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--c-muted);">
                                {{ $quiz->description ?: 'No description' }}
                            </div>
                            <div class="quiz-status" style="margin-top: 0.75rem;">
                                @if($status == 'completed')
                                    <span class="badge" style="background: #10b981; color: white;">✓ Completed</span>
                                    <span class="badge">Score: {{ round($attempt->score) }}%</span>
                                @elseif($status == 'in_progress')
                                    <span class="badge" style="background: #f59e0b; color: white;">In Progress</span>
                                    <span class="badge">Started: {{ $attempt->started_at->diffForHumans() }}</span>
                                @else
                                    <span class="badge" style="background: #6b7280; color: white;">Not Started</span>
                                @endif
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                            @if($status == 'completed')
                                <a href="{{ route('quizzes.show', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn-sm">View Results</a>
                            @elseif($status == 'in_progress')
                                <a href="{{ route('quizzes.take', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn btn-primary">Continue →</a>
                            @else
                                <a href="{{ route('quizzes.take', ['cour' => $cour, 'quiz' => $quiz]) }}" class="btn btn-primary">Start Quiz →</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="mc-empty">
                    <span>📝</span>
                    <p>No quizzes available yet for this course.</p>
                </div>
            @endforelse
        </div>

        <div class="dash-card" style="margin-top: 1.5rem;">
            <a href="{{ route('cours.show', $cour) }}" class="btn btn-ghost">← Back to Course</a>
        </div>
    </main>
</div>
