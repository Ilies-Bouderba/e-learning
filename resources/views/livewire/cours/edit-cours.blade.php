<div class="create-course-page">
    <div class="container">
        <div class="cc-header">
            <a href="{{ route('cours.index') }}" class="cc-back">← Back to Courses</a>
            <div>
                <span class="section-tag">Teacher Panel</span>
                <h1 class="cc-title">Edit Course</h1>
                <p class="cc-sub">Update your course details below.</p>
            </div>
        </div>
        <div class="cc-layout">
            <form class="cc-form" wire:submit="save">

                <div class="cc-field">
                    <label class="cc-label">Course Icon</label>
                    <div class="icon-picker">
                        @foreach ($icons as $ico)
                            <button type="button" class="icon-option {{ $icon === $ico ? 'icon-active' : '' }}"
                                wire:click="$set('icon','{{ $ico }}')">{{ $ico }}</button>
                        @endforeach
                    </div>
                    @error('icon')
                        <span class="cc-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="cc-field">
                    <label class="cc-label">Department</label>
                    <select class="cc-input" wire:model="department_id">
                        <option value="">Select a department...</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->icon }} {{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="cc-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="cc-field">
                    <label class="cc-label">Course Title</label>
                    <input type="text" class="cc-input" wire:model.live.debounce.500ms="title"
                        placeholder="e.g. Advanced Mathematics" maxlength="255">
                    @error('title')
                        <span class="cc-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="cc-field">
                    <label class="cc-label">Description <span class="cc-optional">(optional)</span></label>
                    <textarea class="cc-input cc-textarea" wire:model.live.debounce.500ms="description"
                        placeholder="What will students learn?" maxlength="1000" rows="5"></textarea>
                    <div class="cc-char-count"><span>{{ strlen($description ?? '') }}</span> / 1000</div>
                    @error('description')
                        <span class="cc-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="cc-field">
                    <div class="cc-toggle-row">
                        <label class="cc-label">Course Password</label>
                        <label class="cc-toggle">
                            <input type="checkbox" wire:model.live="has_password">
                            <span class="cc-toggle-track"></span>
                            <span class="cc-toggle-label">{{ $has_password ? 'Enabled' : 'Disabled' }}</span>
                        </label>
                    </div>
                    @if ($has_password)
                        <input type="text" class="cc-input" wire:model="password"
                            placeholder="Leave blank to keep current password">
                        @error('password')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror
                    @endif
                </div>

                <div class="cc-actions">
                    <a href="{{ route('cours.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span wire:loading.remove wire:target="save">Save Changes →</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>

            <div class="cc-preview">
                <div class="cc-preview-label">Live Preview</div>
                <div class="course-card">
                    <div class="course-card-header">
                        <span class="course-card-icon">{{ $icon }}</span>
                        <span class="chapters-count">{{ $cour->chapters()->count() }} chapters</span>
                    </div>
                    <h3 class="course-card-title">{{ $title ?: 'Course Title' }}</h3>
                    <p class="course-card-desc">
                        {{ str($description ?: 'Your course description will appear here.')->limit(100) }}</p>
                    <div class="course-card-footer">
                        <div class="teacher-row">
                            <div class="teacher-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <span class="teacher-name">{{ auth()->user()->name }}</span>
                        </div>
                        @if ($has_password)
                            <span class="course-lock">🔒</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
