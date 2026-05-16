<div class="quiz-take-container">
    <div class="dash-layout" style="grid-template-columns: 1fr;">
        <main class="dash-main" style="padding: 1.5rem;">
            <div class="quiz-header" style="margin-bottom: 2rem;">
                <a href="{{ route('cours.show', $cour) }}" class="cc-back">← Back to Course</a>
                <h1 class="cc-title" style="margin-top: 1rem;">{{ $exam->title }}</h1>
                <p class="cc-sub">{{ $exam->description }}</p>
                @if($exam->duration_minutes)
                    <div class="exam-timer" style="margin-top: 1rem; padding: 0.5rem; background: rgba(255,225,77,0.2); border-radius: 8px; display: inline-block;">
                        ⏱️ Duration: {{ $exam->duration_minutes }} minutes
                    </div>
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
