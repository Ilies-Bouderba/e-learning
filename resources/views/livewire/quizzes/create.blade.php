<div class="dash-layout">
    <livewire:course-sidebar :cour="$cour" active="quizzes" />

    <main class="dash-main">
        <div class="create-course-page">
            <div class="container" style="max-width: 100%; padding: 0;">
                <div class="cc-header" style="margin-bottom: 2rem;">
                    <a href="{{ route('teacher.quizzes.index', $cour) }}" class="cc-back">← Back to Quizzes</a>
                    <div>
                        <span class="section-tag">{{ $cour->icon }} {{ $cour->title }}</span>
                        <h1 class="cc-title">Create New Quiz</h1>
                        <p class="cc-sub">Design your quiz with questions and answers</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mc-flash">{{ session('success') }}</div>
                @endif

                <form wire:submit="saveQuiz" class="cc-form" style="max-width: 100%;">
                    <div class="cc-field" style="margin-bottom: 1.5rem;">
                        <label class="cc-label">Quiz Title</label>
                        <input type="text" class="cc-input" wire:model="title" placeholder="e.g. Week 1 Quiz">
                        @error('title')<span class="cc-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="cc-field" style="margin-bottom: 2rem;">
                        <label class="cc-label">Description</label>
                        <textarea class="cc-input cc-textarea" wire:model="description" rows="3" placeholder="What will this quiz cover?"></textarea>
                        @error('description')<span class="cc-error">{{ $message }}</span>@enderror
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
                                        {{ count($question['options']) }} options • {{ $question['points'] }} points
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="empty-msg">No questions added yet. Click "Add Question" to start.</p>
                        @endif
                    </div>

                    <div class="cc-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1.5px solid rgba(15,14,23,0.1);">
                        <a href="{{ route('teacher.quizzes.index', $cour) }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span wire:loading.remove wire:target="saveQuiz">Create Quiz →</span>
                            <span wire:loading wire:target="saveQuiz">Creating...</span>
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
                            <textarea class="cc-input" wire:model="questions.{{ $currentQuestionIndex }}.question_text" rows="3" placeholder="Enter your question here..."></textarea>
                        </div>

                        <div class="cc-field" style="margin-bottom: 1rem;">
                            <label class="cc-label">Points</label>
                            <input type="number" class="cc-input" wire:model="questions.{{ $currentQuestionIndex }}.points" min="1" style="max-width: 100px;">
                        </div>

                        <div class="cc-field" style="margin-bottom: 1rem;">
                            <div class="dash-card-header" style="margin-bottom: 0.75rem;">
                                <h4>Options</h4>
                                <button type="button" class="btn-sm" wire:click="addOption({{ $currentQuestionIndex }})">+ Add Option</button>
                            </div>
                            @if(isset($questions[$currentQuestionIndex]['options']) && count($questions[$currentQuestionIndex]['options']) > 0)
                                @foreach($questions[$currentQuestionIndex]['options'] as $optIndex => $option)
                                    <div class="option-card" style="border: 1px solid rgba(15,14,23,0.1); border-radius: 8px; padding: 0.75rem; margin-bottom: 0.75rem;">
                                        <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                                            <div style="flex: 1;">
                                                <input type="text" class="cc-input" placeholder="Option text" wire:model="questions.{{ $currentQuestionIndex }}.options.{{ $optIndex }}.option_text">
                                            </div>
                                            <div style="display: flex; gap: 0.75rem; align-items: center;">
                                                <label style="display: flex; align-items: center; gap: 0.25rem; cursor: pointer;">
                                                    <input type="checkbox" wire:model="questions.{{ $currentQuestionIndex }}.options.{{ $optIndex }}.is_correct">
                                                    Correct
                                                </label>
                                                <button type="button" class="btn-sm btn-danger" wire:click="removeOption({{ $currentQuestionIndex }}, {{ $optIndex }})">×</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="empty-msg" style="padding: 1rem; text-align: center;">No options yet. Click "Add Option" to start.</p>
                            @endif
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
