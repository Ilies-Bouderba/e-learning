<div class="dash-layout" style="grid-template-columns: 1fr;">
    <main class="dash-main">
        <div class="course-show-header">
            <div class="csh-left">
                <div class="csh-icon">{{ $course->icon }}</div>
                <div>
                    <div class="csh-dept">{{ $course->department->icon }} {{ $course->department->name }}</div>
                    <h1 class="csh-title">{{ $course->title }} - Exams</h1>
                    <p class="csh-desc">All available exams for this course</p>
                </div>
            </div>
            <div class="csh-actions">
                <a href="{{ route('cours.show', $course) }}" class="btn btn-ghost">← Back to Course</a>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">📝 Exams</h2>
                <span class="badge">{{ $exams->count() }} total</span>
            </div>

            @forelse($exams as $exam)
                @php
                    $attempt = \App\Models\ExamAttempt::where('student_id', auth()->id())
                        ->where('exam_id', $exam->id)
                        ->first();
                    $status = $attempt ? ($attempt->completed_at ? 'completed' : 'in_progress') : 'not_started';
                @endphp
                <div class="exam-item" style="margin-bottom: 1rem; padding: 1rem; border-bottom: 1px solid rgba(15,14,23,0.08); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="exam-title" style="font-weight: 700;">{{ $exam->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--c-muted);">{{ $exam->questions->count() }} questions • {{ $exam->total_score }} points</div>
                        @if($exam->duration_minutes)
                            <div style="font-size: 0.7rem; color: var(--c-muted);">⏱️ {{ $exam->duration_minutes }} minutes</div>
                        @endif
                        @if($status == 'completed')
                            <div style="font-size: 0.7rem; color: #10b981; margin-top: 0.25rem;">Score: {{ $exam->total_score > 0 ? round(($attempt->total_score / $exam->total_score) * 100) : 0 }}%</div>
                        @elseif($status == 'in_progress')
                            <div style="font-size: 0.7rem; color: #f59e0b; margin-top: 0.25rem;">In Progress</div>
                        @endif
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        @if($status == 'completed')
                            <a href="{{ route('exams.show', ['course' => $course, 'exam' => $exam]) }}" class="btn-sm">View Results</a>
                        @elseif($status == 'in_progress')
                            <a href="{{ route('student.exams.take', ['course' => $course, 'exam' => $exam]) }}" class="btn-sm btn-primary">Continue →</a>
                        @else
                            <a href="{{ route('student.exams.take', ['course' => $course, 'exam' => $exam]) }}" class="btn-sm btn-primary">Start Exam →</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="mc-empty">
                    <span>📝</span>
                    <p>No exams available yet for this course.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
