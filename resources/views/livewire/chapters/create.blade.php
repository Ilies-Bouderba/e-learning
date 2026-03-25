{{-- resources/views/livewire/chapters/create.blade.php --}}
<div class="create-course-page">
    <div class="container">

        <div class="cc-header">
            <a href="{{ route('cours.show', $cour) }}" class="cc-back">← Back to Course</a>
            <div>
                <span class="section-tag">{{ $cour->icon }} {{ $cour->title }}</span>
                <h1 class="cc-title">Add Chapter</h1>
                <p class="cc-sub">Chapter {{ $chapter_number }} of your course.</p>
            </div>
        </div>

        <form class="cc-form" wire:submit="save" style="max-width:680px;">

            <div class="cc-field">
                <label class="cc-label">Chapter Number</label>
                <input type="number" class="cc-input" wire:model="chapter_number" min="1" style="max-width:120px;">
                @error('chapter_number') <span class="cc-error">{{ $message }}</span> @enderror
            </div>

            <div class="cc-field">
                <label class="cc-label">Chapter Title</label>
                <input type="text" class="cc-input" wire:model="title"
                    placeholder="e.g. Introduction to Derivatives" maxlength="255">
                @error('title') <span class="cc-error">{{ $message }}</span> @enderror
            </div>

            <div class="cc-field">
                <label class="cc-label">Content <span class="cc-optional">(optional)</span></label>
                <textarea class="cc-input cc-textarea" wire:model="content"
                    placeholder="Chapter content, notes, or instructions..." rows="8"></textarea>
                @error('content') <span class="cc-error">{{ $message }}</span> @enderror
            </div>

            <div class="cc-actions">
                <a href="{{ route('cours.show', $cour) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <span wire:loading.remove wire:target="save">Add Chapter →</span>
                    <span wire:loading wire:target="save">Adding...</span>
                </button>
            </div>

        </form>
    </div>
</div>
