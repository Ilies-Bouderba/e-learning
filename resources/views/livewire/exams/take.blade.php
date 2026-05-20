<div class="quiz-take-container">
    <div class="dash-layout" style="grid-template-columns: 1fr;">
        <main class="dash-main" style="padding: 1.5rem;">
            <div class="quiz-header" style="margin-bottom: 2rem;">
                <a href="{{ route('cours.show', $course) }}" class="cc-back">← Back to Course</a>
                <h1 class="cc-title" style="margin-top: 1rem;">{{ $exam->title }}</h1>
                <p class="cc-sub">{{ $exam->description }}</p>
                @if($exam->duration_minutes)
                <div id="exam-timer" style="position: sticky; top: 0; z-index: 100; background: var(--c-bg); border-bottom: var(--border); padding: 0.75rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 700;">⏱️ Time Remaining:</span>
                    <span id="timer-display" style="font-size: 1.5rem; font-weight: 800; color: var(--c-yellow);"></span>
                </div>

                <script>
                    (function() {
                        const startedAt = new Date("{{ $attempt->started_at->toISOString() }}");
                        const durationMs = {{ $exam->duration_minutes }} * 60 * 1000;
                        const endTime = new Date(startedAt.getTime() + durationMs);

                        function updateTimer() {
                            const now = new Date();
                            const remaining = endTime - now;

                            if (remaining <= 0) {
                                document.getElementById('timer-display').textContent = '00:00';
                                document.getElementById('timer-display').style.color = '#ef4444';
                                // Auto submit
                                @this.call('submitExam');
                                return;
                            }

                            if (remaining < 300000) { // less than 5 minutes
                                document.getElementById('timer-display').style.color = '#ef4444';
                            } else if (remaining < 600000) { // less than 10 minutes
                                document.getElementById('timer-display').style.color = '#f59e0b';
                            }

                            const minutes = Math.floor(remaining / 60000);
                            const seconds = Math.floor((remaining % 60000) / 1000);
                            document.getElementById('timer-display').textContent =
                                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

                            setTimeout(updateTimer, 1000);
                        }

                        updateTimer();
                    })();
                </script>
                @endif
                <div class="quiz-progress" style="margin-top: 1rem;">
                    <div style="background: rgba(15,14,23,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="width: {{ $progress }}%; background: var(--c-yellow); height: 100%;"></div>
                    </div>
                    <p style="margin-top: 0.5rem; font-size: 0.85rem;">Question {{ $currentQuestion + 1 }} of {{ count($questions) }}</p>
                </div>
            </div>

            @if($currentQuestionData)
            <div class="exam-question" style="border: var(--border); border-radius: var(--radius); padding: 2rem; margin-bottom: 2rem;">
                <h3 style="font-family: var(--font-head); margin-bottom: 1.5rem;">{{ $currentQuestionData->question_text }}</h3>
                <div class="exam-answer" style="margin-top: 1rem;">
                    <label class="cc-label">Your Answer:</label>
                    <textarea
                        wire:change="saveAnswer({{ $currentQuestionData->id }}, $event.target.value)"
                        rows="6"
                        class="cc-input"
                        style="width: 100%; margin-top: 0.5rem; font-family: monospace;"
                        placeholder="Type your answer here...">{{ $answers[$currentQuestionData->id] ?? '' }}</textarea>
                </div>
            </div>
            @endif

            <div class="quiz-navigation" style="display: flex; justify-content: space-between; gap: 1rem;">
                <button type="button" class="btn btn-ghost" wire:click="previousQuestion" @if($currentQuestion == 0) disabled @endif>
                    ← Previous
                </button>
                @if($currentQuestion < count($questions) - 1)
                <button type="button" class="btn btn-primary" wire:click="nextQuestion">
                    Next →
                </button>
                @else
                <button type="button" class="btn btn-primary" wire:click="submitExam" wire:confirm="Are you sure you want to submit this exam?">
                    Submit Exam →
                </button>
                @endif
            </div>
        </main>
    </div>
</div>
