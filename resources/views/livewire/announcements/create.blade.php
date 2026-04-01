<div class="create-course-page">
    <div class="container">
        <div class="cc-header">
            <a href="{{ route('cours.show', $cour) }}" class="cc-back">← Back to Course</a>
            <div>
                <span class="section-tag">{{ $cour->icon }} {{ $cour->title }}</span>
                <h1 class="cc-title">New Announcement</h1>
                <p class="cc-sub">Post an announcement to all enrolled students.</p>
            </div>
        </div>
        <form class="cc-form" wire:submit="save" style="max-width:680px;">
            <div class="cc-field">
                <label class="cc-label">Title</label>
                <input type="text" class="cc-input" wire:model="title" placeholder="e.g. Exam date changed"
                    maxlength="255">
                @error('title')
                    <span class="cc-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="cc-field">
                <label class="cc-label">Message</label>
                <textarea class="cc-input cc-textarea" wire:model="content" placeholder="Write your announcement here..."
                    rows="6"></textarea>
                @error('content')
                    <span class="cc-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="cc-actions">
                <a href="{{ route('cours.show', $cour) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <span wire:loading.remove wire:target="save">Post Announcement →</span>
                    <span wire:loading wire:target="save">Posting...</span>
                </button>
            </div>
        </form>
    </div>
</div>
