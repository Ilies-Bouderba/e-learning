@if(auth()->user()->isStudent())
    <div class="dash-layout" style="grid-template-columns: 1fr;">
        <main class="dash-main">
            <div class="course-show-header">
                <div class="csh-left">
                    <div class="csh-icon">{{ $course->icon }}</div>
                    <div>
                        <div class="csh-dept">{{ $course->department->icon }} {{ $course->department->name }}</div>
                        <h1 class="csh-title">{{ $quiz->title }} - Results</h1>
                        <p class="csh-desc">Your quiz results and answers</p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mc-flash">{{ session('success') }}</div>
            @endif

            <div class="dash-card" style="margin-bottom: 1.5rem;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Your Score</h2>
                </div>
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 4rem; font-weight: 800; color: var(--c-yellow);">{{ round($studentAttempt->score) }}%</div>
                    <p style="margin-top: 0.5rem; color: var(--c-muted);">Completed: {{ $studentAttempt->completed_at->format('F j, Y g:i A') }}</p>
                    @php
                        $correctCount = 0;
                        foreach($quiz->questions as $question) {
                            $correctOption = $question->options->where('is_correct', true)->first();
                            $userAnswer = $studentAttempt->answers[$question->id] ?? null;
                            if ($correctOption && $userAnswer && $userAnswer == $correctOption->id) {
                                $correctCount++;
                            }
                        }
                    @endphp
                    <p style="font-size: 0.85rem; color: var(--c-muted);">You answered {{ $correctCount }} out of {{ $quiz->questions->count() }} questions correctly.</p>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Review Your Answers</h2>
                </div>
                @foreach($quiz->questions as $index => $question)
                    @php
                        $userAnswer = $studentAttempt->answers[$question->id] ?? null;
                        $correctOption = $question->options->where('is_correct', true)->first();
                        $isCorrect = $correctOption && $userAnswer && $userAnswer == $correctOption->id;
                    @endphp
                    <div class="question-item" style="padding: 1.5rem; border-bottom: 1px solid rgba(15,14,23,0.08);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div><strong>Question {{ $index + 1 }}:</strong> {{ $question->question_text }}</div>
                                <div style="margin-top: 0.75rem; margin-left: 1rem;">
                                    @foreach($question->options as $option)
                                        <div style="margin-bottom: 0.5rem;">
                                            @if($correctOption && $option->id == $correctOption->id)
                                                <span style="color: #10b981;">✓</span> <strong style="color: #10b981;">{{ $option->option_text }}</strong> <span style="font-size: 0.7rem; color: #10b981;">(Correct Answer)</span>
                                            @elseif($option->id == $userAnswer && !$isCorrect)
                                                <span style="color: #ef4444;">✗</span> <span style="color: #ef4444;">{{ $option->option_text }}</span> <span style="font-size: 0.7rem; color: #ef4444;">(Your Answer)</span>
                                            @else
                                                <span style="color: var(--c-muted);">○</span> {{ $option->option_text }}
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if(!$correctOption)
                                    <div style="margin-top: 0.5rem; color: #ef4444; font-size: 0.75rem;">
                                        ⚠️ No correct answer set for this question.
                                    </div>
                                @endif
                            </div>
                            <div style="flex-shrink: 0; margin-left: 1rem;">
                                @if($isCorrect)
                                    <span class="badge" style="background: #10b981; color: white; padding: 0.25rem 0.75rem;">+{{ $question->points }} pts</span>
                                @else
                                    <span class="badge" style="background: #ef4444; color: white; padding: 0.25rem 0.75rem;">0 pts</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <div style="margin-top: 2rem; padding-top: 1rem; text-align: center;">
                    <a href="{{ route('cours.show', $course) }}" class="btn btn-primary">Back to Course</a>
                </div>
            </div>
        </main>
    </div>
@elseif(auth()->user()->isTeacher())
    <div class="dash-layout">
        <livewire:course-sidebar :course="$course" active="quizzes" />

        <main class="dash-main">
            <div class="dash-header">
                <div>
                    <div class="csh-dept">{{ $course->department->icon }} {{ $course->department->name }}</div>
                    <h1 class="dash-title">{{ $quiz->title }}</h1>
                    <p class="dash-subtitle">{{ $quiz->description ?: 'Quiz results and analytics' }}</p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('teacher.quizzes.edit', ['course' => $course, 'quiz' => $quiz]) }}" class="btn btn-primary">Edit Quiz</a>
                    <a href="{{ route('teacher.quizzes.index', $course) }}" class="btn btn-ghost">← Back</a>
                </div>
            </div>

            @if(session('success'))
                <div class="mc-flash">{{ session('success') }}</div>
            @endif

            <div class="dash-grid">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2 class="dash-card-title">Quiz Details</h2>
                    </div>
                    <div style="padding: 0.5rem 0;">
                        <p><strong>Questions:</strong> {{ $quiz->questions->count() }}</p>
                        <p><strong>Total Points:</strong> {{ $quiz->questions->sum('points') }}</p>
                        <p><strong>Status:</strong>
                            @if($quiz->is_published)
                                <span class="badge" style="background: #10b981; color: white;">Published</span>
                            @else
                                <span class="badge" style="background: #6b7280; color: white;">Draft</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2 class="dash-card-title">Statistics</h2>
                    </div>
                    <div style="padding: 0.5rem 0;">
                        <p><strong>Total Attempts:</strong> {{ $studentAttempts->count() }}</p>
                        <p><strong>Average Score:</strong> {{ round($studentAttempts->avg('score'), 1) }}%</p>
                        <p><strong>Highest Score:</strong> {{ round($studentAttempts->max('score'), 1) }}%</p>
                        <p><strong>Lowest Score:</strong> {{ round($studentAttempts->min('score'), 1) }}%</p>
                    </div>
                </div>
            </div>

            <div class="dash-card" style="margin-top: 1.5rem;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Student Attempts</h2>
                </div>
                <div class="students-list">
                    @forelse($studentAttempts as $attempt)
                    <div class="student-item" style="padding: 1rem; border-bottom: 1px solid rgba(15,14,23,0.08);">
                        <div class="student-avatar">{{ strtoupper(substr($attempt->student->name, 0, 2)) }}</div>
                        <div class="student-info">
                            <div class="student-name">{{ $attempt->student->name }}</div>
                            <div class="student-course">{{ $attempt->student->email }}</div>
                        </div>
                        <div class="student-progress">
                            @if($attempt->completed_at)
                                <span class="badge" style="background: #10b981; color: white;">Completed</span>
                                <span class="student-pct" style="margin-left: 0.5rem;">{{ round($attempt->score) }}%</span>
                            @else
                                <span class="badge" style="background: #f59e0b;">In Progress</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="empty-msg">No student attempts yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="dash-card" style="margin-top: 1.5rem;">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Questions & Answers</h2>
                </div>
                @foreach($quiz->questions as $index => $question)
                    @php
                        $correctOption = $question->options->where('is_correct', true)->first();
                    @endphp
                    <div class="question-item" style="padding: 1rem; border-bottom: 1px solid rgba(15,14,23,0.08);">
                        <div><strong>Q{{ $index + 1 }}:</strong> {{ $question->question_text }}</div>
                        <div style="font-size: 0.75rem; color: var(--c-muted); margin-top: 0.25rem;">Points: {{ $question->points }}</div>
                        <div style="margin-top: 0.5rem; margin-left: 1rem;">
                            @foreach($question->options as $option)
                                <div style="margin-bottom: 0.25rem;">
                                    @if($option->is_correct)
                                        ✓ <strong style="color: #10b981;">{{ $option->option_text }}</strong>
                                    @else
                                        ○ {{ $option->option_text }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if(!$correctOption)
                            <div style="margin-top: 0.5rem; color: #ef4444; font-size: 0.75rem;">
                                ⚠️ No correct answer set for this question.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </main>
    </div>
@endif
