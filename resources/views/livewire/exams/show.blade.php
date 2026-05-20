@if(auth()->user()->isStudent())
    <div class="dash-layout" style="grid-template-columns: 1fr;">
        <main class="dash-main">
            <div class="course-show-header">
                <div class="csh-left">
                    <div class="csh-icon">{{ $course->icon }}</div>
                    <div>
                        <div class="csh-dept">{{ $course->department->icon }} {{ $course->department->name }}</div>
                        <h1 class="csh-title">{{ $exam->title }} - Results</h1>
                        <p class="csh-desc">Your exam results and answers</p>
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
                    @if($studentAttempt->is_graded)
                        <div style="font-size: 4rem; font-weight: 800; color: var(--c-yellow);">
                            {{ $exam->total_score > 0 ? round(($studentAttempt->total_score / $exam->total_score) * 100) : 0 }}%
                        </div>
                        <p style="margin-top: 0.5rem; color: var(--c-muted);">
                            Score: {{ $studentAttempt->total_score }} / {{ $exam->total_score }} points
                        </p>
                    @else
                        <div style="font-size: 2rem; font-weight: 800; color: var(--c-yellow);">
                            ⏳ Pending Grading
                        </div>
                        <p style="margin-top: 0.5rem; color: var(--c-muted);">
                            Your exam has been submitted. The teacher will grade it soon.
                        </p>
                    @endif
                    <p style="margin-top: 0.5rem; color: var(--c-muted);">
                        Completed: {{ $studentAttempt->completed_at->format('F j, Y g:i A') }}
                    </p>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">Review Your Answers</h2>
                </div>
                @foreach($exam->questions as $index => $question)
                    @php
                        $userAnswer = $studentAttempt->answers[$question->id] ?? 'No answer provided';
                        $aiGrade = $studentAttempt->ai_grades[$question->id] ?? null;
                    @endphp
                    <div class="question-item" style="padding: 1.5rem; border-bottom: 1px solid rgba(15,14,23,0.08);">
                        <div><strong>Question {{ $index + 1 }}:</strong> {{ $question->question_text }}</div>
                        <div style="margin-top: 0.75rem; margin-left: 1rem;">
                            <div><strong>Your Answer:</strong></div>
                            <div style="background: #fafaf8; padding: 0.75rem; border-radius: 8px; margin-top: 0.25rem;">
                                {{ $userAnswer }}
                            </div>
                        </div>
                        @if($studentAttempt->is_graded && $aiGrade)
                            <div style="margin-top: 0.75rem; margin-left: 1rem;">
                                <div><strong>Feedback:</strong></div>
                                <div style="background: rgba(255,225,77,0.1); padding: 0.75rem; border-radius: 8px; margin-top: 0.25rem;">
                                    <div>Score: {{ $aiGrade['score'] }} / {{ $question->points }} points</div>
                                    @if(isset($aiGrade['correct_answer']))
                                        <div style="margin-top: 0.5rem;">
                                            <strong>Correct Answer:</strong> {{ $aiGrade['correct_answer'] }}
                                        </div>
                                    @endif
                                    <div style="margin-top: 0.5rem;">{{ $aiGrade['feedback'] }}</div>
                                    @if(isset($aiGrade['reason']))
                                        <div style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--c-muted);">
                                                            <strong>Reason:</strong> {{ $aiGrade['reason'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
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
        <livewire:course-sidebar :course="$course" active="exams" />

        <main class="dash-main">
            <div class="dash-header">
                <div>
                    <div class="csh-dept">{{ $course->department->icon }} {{ $course->department->name }}</div>
                    <h1 class="dash-title">{{ $exam->title }}</h1>
                    <p class="dash-subtitle">{{ $exam->description ?: 'Exam results and analytics' }}</p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('teacher.exams.edit', ['course' => $course, 'exam' => $exam]) }}" class="btn btn-primary">Edit Exam</a>
                    <a href="{{ route('teacher.exams.index', $course) }}" class="btn btn-ghost">← Back</a>
                </div>
            </div>

            @if(session('success'))
                <div class="mc-flash">{{ session('success') }}</div>
            @endif

            <div class="dash-grid">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2 class="dash-card-title">Exam Details</h2>
                    </div>
                    <div style="padding: 0.5rem 0;">
                        <p><strong>Questions:</strong> {{ $exam->questions->count() }}</p>
                        <p><strong>Total Points:</strong> {{ $exam->total_score }}</p>
                        @if($exam->duration_minutes)
                            <p><strong>Duration:</strong> {{ $exam->duration_minutes }} minutes</p>
                        @endif
                        <p><strong>Status:</strong>
                            @if($exam->is_published)
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
                        <p><strong>Total Attempts:</strong> {{ collect($studentAttempts)->count() }}</p>
                        <p><strong>Average Score:</strong> {{ round(collect($studentAttempts)->avg('total_score'), 1) }} / {{ $exam->total_score }}</p>
                        <p><strong>Highest Score:</strong> {{ round(collect($studentAttempts)->max('total_score'), 1) }} / {{ $exam->total_score }}</p>
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
                        <div class="student-avatar">{{ strtoupper(substr($attempt['student']['name'] ?? 'UN', 0, 2)) }}</div>
                        <div class="student-info">
                            <div class="student-name">{{ $attempt['student']['name'] ?? 'Unknown' }}</div>
                            <div class="student-course">{{ $attempt['student']['email'] ?? '' }}</div>
                        </div>
                        <div class="student-progress">
                            @if($attempt['completed_at'])
                                @if($attempt['is_graded'])
                                    <span class="badge" style="background: #10b981; color: white;">Graded</span>
                                    <span class="student-pct" style="margin-left: 0.5rem;">{{ $attempt['total_score'] }}/{{ $exam->total_score }}</span>
                                @else
                                    <span class="badge" style="background: #f59e0b;">Pending</span>
                                @endif
                            @else
                                <span class="badge" style="background: #ef4444;">In Progress</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="empty-msg">No student attempts yet.</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
@endif
