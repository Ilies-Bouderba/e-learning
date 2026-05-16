<div class="quiz-take-container">
    <div class="dash-layout" style="grid-template-columns: 1fr;">
        <main class="dash-main" style="padding: 1.5rem;">
            <div class="quiz-header" style="margin-bottom: 2rem;">
                <a href="{{ route('cours.show', $cour) }}" class="cc-back">← Back to Course</a>
                <h1 class="cc-title" style="margin-top: 1rem;">{{ $quiz->title }}</h1>
                <p class="cc-sub">{{ $quiz->description }}</p>
                <div class="quiz-progress" style="margin-top: 1rem;">
                    <div style="background: rgba(15,14,23,0.1); height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="width: {{ $progress }}%; background: var(--c-yellow); height: 100%;"></div>
                    </div>
                    <p style="margin-top: 0.5rem; font-size: 0.85rem;">Question {{ $currentQuestion + 1 }} of {{ count($questions) }}</p>
                </div>
            </div>

            @if($currentQuestionData)
            <div class="quiz-question" style="border: var(--border); border-radius: var(--radius); padding: 2rem; margin-bottom: 2rem;">
                <h3 style="font-family: var(--font-head); margin-bottom: 1.5rem;">{{ $currentQuestionData->question_text }}</h3>
                <div class="quiz-options" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($currentQuestionData->options as $option)
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="radio"
                               name="question_{{ $currentQuestionData->id }}"
                               value="{{ $option->id }}"
                               wire:change="saveAnswer({{ $currentQuestionData->id }}, {{ $option->id }})"
                               @if(($answers[$currentQuestionData->id] ?? null) == $option->id) checked @endif>
                        <span>{{ $option->option_text }}</span>
                    </label>
                    @endforeach
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
                <button type="button" class="btn btn-primary" wire:click="submitQuiz" wire:confirm="Are you sure you want to submit this quiz?">
                    Submit Quiz →
                </button>
                @endif
            </div>
        </main>
    </div>
</div>
