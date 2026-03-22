{{-- resources/views/livewire/cours/create-cour.blade.php --}}

<div class="create-course-page">
    <div class="container">

        {{-- Page header --}}
        <div class="cc-header">
            <a href="{{ route('dashboard.teacher') }}" class="cc-back">← Back to Courses</a>
            <div>
                <span class="section-tag">Teacher Panel</span>
                <h1 class="cc-title">Create a New Course</h1>
                <p class="cc-sub">Fill in the details below to publish your course.</p>
            </div>
        </div>

        <div class="cc-layout">

            {{-- FORM --}}
            <form class="cc-form" wire:submit="save">

                {{-- Icon picker --}}
                <div class="cc-field">
                    <label class="cc-label">Course Icon</label>
                    <div class="icon-picker">
                        @foreach($icons as $ico)
                            <button
                                type="button"
                                class="icon-option {{ $icon === $ico ? 'icon-active' : '' }}"
                                wire:click="$set('icon', '{{ $ico }}')"
                            >{{ $ico }}</button>
                        @endforeach
                    </div>
                    @error('icon') <span class="cc-error">{{ $message }}</span> @enderror
                </div>

                {{-- Title --}}
                <div class="cc-field">
                    <label class="cc-label" for="title">Course Title</label>
                    <input
                        type="text"
                        id="title"
                        class="cc-input"
                        wire:model.live.debounce.500ms="title"
                        placeholder="e.g. Advanced Mathematics"
                        maxlength="255"
                    >
                    @error('title') <span class="cc-error">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div class="cc-field">
                    <label class="cc-label" for="description">Description <span class="cc-optional">(optional)</span></label>
                    <textarea
                        id="description"
                        class="cc-input cc-textarea"
                        wire:model.live.debounce.500ms="description"
                        placeholder="What will students learn in this course?"
                        maxlength="1000"
                        rows="5"
                    ></textarea>
                    <div class="cc-char-count">
                        <span>{{ strlen($description ?? '') }}</span> / 1000
                    </div>
                    @error('description') <span class="cc-error">{{ $message }}</span> @enderror
                </div>

                {{-- Submit --}}
                <div class="cc-actions">
                    <a href="/courses" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span wire:loading.remove wire:target="save">Publish Course →</span>
                        <span wire:loading wire:target="save">Publishing...</span>
                    </button>
                </div>

            </form>

            {{-- PREVIEW CARD --}}
            <div class="cc-preview">
                <div class="cc-preview-label">Live Preview</div>
                <div class="course-card">
                    <div class="course-card-header">
                        <span class="course-card-icon">{{ $icon }}</span>
                        <span class="chapters-count">0 chapters</span>
                    </div>
                    <h3 class="course-card-title">
                        <span>{{ $title ?: 'Course Title' }}</span>
                    </h3>
                    <p class="course-card-desc">
                        <span>{{ str($description ?: 'Your course description will appear here.')->limit(100) }}</span>
                    </p>
                    <div class="course-card-footer">
                        <div class="teacher-row">
                            <div class="teacher-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <span class="teacher-name">{{ auth()->user()->name }}</span>
                        </div>
                        <span class="btn-sm">View →</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
