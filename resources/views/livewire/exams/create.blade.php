<div class="dash-layout">
    <livewire:course-sidebar :cour="$cour" active="exams" />

    <main class="dash-main">
        <div class="create-course-page">
            <div class="container" style="max-width: 100%; padding: 0;">
                <div class="cc-header" style="margin-bottom: 2rem;">
                    <a href="{{ route('teacher.exams.index', $cour) }}" class="cc-back">← Back to Exams</a>
                    <div>
                        <span class="section-tag">{{ $cour->icon }} {{ $cour->title }}</span>
                        <h1 class="cc-title">Create New Exam</h1>
                        <p class="cc-sub">Design your exam with questions (AI-graded text answers)</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mc-flash">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="mc-flash" style="background: #fee2e2; color: #dc2626;">{{ session('error') }}</div>
                @endif

                <form wire:submit="saveExam" class="cc-form" style="max-width: 100%;">
                    <div class="cc-field" style="margin-bottom: 1.5rem;">
                        <label class="cc-label">Exam Title</label>
                        <input type="text" class="cc-input" wire:model="title" placeholder="e.g. Mid-Term Exam">
                        @error('title')<span class="cc-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="cc-field" style="margin-bottom: 1.5rem;">
                        <label class="cc-label">Description</label>
                        <textarea class="cc-input cc-textarea" wire:model="description" rows="3" placeholder="What does this exam cover?"></textarea>
                        @error('description')<span class="cc-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="cc-field" style="margin-bottom: 1.5rem;">
                        <label class="cc-label">Duration (minutes) - Optional</label>
                        <input type="number" class="cc-input" wire:model="duration_minutes" placeholder="e.g. 60" style="max-width: 200px;">
                        @error('duration_minutes')<span class="cc-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="cc-field" style="margin-bottom: 1.5rem;">
                        <label class="cc-label">Start Date & Time (when exam becomes available)</label>
                        <input type="datetime-local" class="cc-input" wire:model="start_date" style="max-width: 250px;">
                        @error('start_date')<span class="cc-error">{{ $message }}</span>@enderror
                        <div style="font-size: 0.7rem; color: var(--c-muted); margin-top: 0.25rem;">Leave empty to make exam available immediately.</div>
                    </div>

                    <div class="cc-field" style="margin-bottom: 1.5rem;">
                        <label class="cc-label">End Date & Time (when exam closes)</label>
                        <input type="datetime-local" class="cc-input" wire:model="end_date" style="max-width: 250px;">
                        @error('end_date')<span class="cc-error">{{ $message }}</span>@enderror
                        <div style="font-size: 0.7rem; color: var(--c-muted); margin-top: 0.25rem;">Leave empty to keep exam open indefinitely.</div>
                    </div>

                    <div class="cc-field" style="margin-bottom: 2rem;">
                        <div class="dash-card-header" style="margin-bottom: 1rem;">
                            <h2 class="dash-card-title">Questions</h2>
                            <button type="button" class="btn-sm" wire:click="addQuestion">+ Add Question</button>
                        </div>

                        @if(count($questions) > 0)
                            @foreach($questions as $index => $question)
                                <div class="question-card" style="border: 1.5px solid rgba(15,14,23,0.15); border-radius: 12px; padding: 1rem; margin-bottom: 1rem; background: #fafaf8;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                        <strong>Question {{ $index + 1 }}</strong>
                                        <div>
                                            <button type="button" class="btn-sm" wire:click="editQuestion({{ $index }})">Edit</button>
                                            <button type="button" class="btn-sm btn-danger" wire:click="removeQuestion({{ $index }})">Remove</button>
                                        </div>
                                    </div>
                                    <div>{{ Str::limit($question['question_text'], 100) ?: 'No question text yet' }}</div>
                                    <div style="font-size: 0.75rem; color: var(--c-muted); margin-top: 0.25rem;">
                                        Points: {{ $question['points'] }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="empty-msg">No questions added yet. Click "Add Question" to start.</p>
                        @endif
                    </div>

                    <div class="cc-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1.5px solid rgba(15,14,23,0.1);">
                        <a href="{{ route('teacher.exams.index', $cour) }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span wire:loading.remove wire:target="saveExam">Create Exam →</span>
                            <span wire:loading wire:target="saveExam">Creating...</span>
                        </button>
                    </div>
                </form>

                @if($showQuestionForm)
                <div class="modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 1000;">
                    <div class="modal-content" style="background: var(--c-bg); border: var(--border); border-radius: var(--radius); max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; padding: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <h3 style="font-family: var(--font-head); font-size: 1.25rem; font-weight: 800;">{{ $currentQuestionIndex !== null && isset($questions[$currentQuestionIndex]) ? 'Edit' : 'Add' }} Question</h3>
                            <button @click="$wire.cancelQuestionForm()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
                        </div>

                        <div class="cc-field" style="margin-bottom: 1rem;">
                            <label class="cc-label">Question Text</label>
                            <textarea class="cc-input" wire:model="questions.{{ $currentQuestionIndex }}.question_text" rows="5" placeholder="Enter your question here..."></textarea>
                        </div>

                        <div class="cc-field" style="margin-bottom: 1rem;">
                            <label class="cc-label">Points</label>
                            <input type="number" class="cc-input" wire:model="questions.{{ $currentQuestionIndex }}.points" min="1" style="max-width: 120px;">
                        </div>

                        <div style="background: rgba(255,225,77,0.15); padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; border-left: 3px solid var(--c-yellow);">
                            <div style="font-size: 0.8rem; color: var(--c-dark);">
                                <strong>💡 AI Grading</strong><br>
                                The AI will automatically grade student answers based on the question. No reference answer needed.
                            </div>
                        </div>

                        <div class="cc-actions" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1.5px solid rgba(15,14,23,0.1);">
                            <button type="button" class="btn btn-primary" wire:click="cancelQuestionForm">Done</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: var(--c-bg);
    border: var(--border);
    border-radius: var(--radius);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 2rem;
}
[x-cloak] { display: none !important; }
</style>
