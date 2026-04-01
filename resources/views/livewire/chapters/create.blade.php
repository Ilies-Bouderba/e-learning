<div class="dash-layout">
    <livewire:course-sidebar :cour="$cour" active="chapters" />

    <main class="dash-main">
        <div class="create-course-page">
            <div class="container" style="max-width: 100%; padding: 0;">
                <div class="cc-header">
                    <a href="{{ route('cours.show', $cour) }}" class="cc-back">← Back to Course</a>
                    <div>
                        <span class="section-tag">{{ $cour->icon }} {{ $cour->title }}</span>
                        <h1 class="cc-title">Add New Chapter</h1>
                        <p class="cc-sub">Chapter {{ $chapter_number }} of your course</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mc-flash">{{ session('success') }}</div>
                @endif

                <form wire:submit="save" class="cc-form" style="max-width: 100%;">
                    <div class="cc-field">
                        <label class="cc-label">Chapter Number</label>
                        <input type="number" class="cc-input" wire:model="chapter_number" min="1"
                            style="max-width: 120px;">
                        @error('chapter_number')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Chapter Title</label>
                        <input type="text" class="cc-input" wire:model="title"
                            placeholder="e.g. Introduction to Derivatives">
                        @error('title')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Content</label>
                        <textarea class="cc-input cc-textarea" wire:model="content" rows="8"
                            placeholder="Write the chapter content here..."></textarea>
                        @error('content')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Attachments Section --}}
                    <div class="cc-field">
                        <label class="cc-label">Attachments (optional)</label>
                        <div class="attachments-container" style="display: flex; flex-direction: column; gap: 1.5rem;">
                            @foreach ($attachments as $index => $attachment)
                                <div class="attachment-card"
                                    style="border: 1.5px solid rgba(15,14,23,0.15); border-radius: 12px; padding: 1.25rem; background: #fafaf8;">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                        <strong style="font-family: var(--font-head);">Attachment
                                            {{ $index + 1 }}</strong>
                                        <button type="button" class="btn-sm btn-danger"
                                            wire:click="removeAttachment({{ $index }})"
                                            style="background: #ef4444; color: white;">Remove</button>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div class="cc-field">
                                            <label>File</label>
                                            <input type="file" wire:model="attachments.{{ $index }}.file"
                                                class="cc-input" style="padding: 0.5rem;">
                                            @error("attachments.{$index}.file")
                                                <span class="cc-error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="cc-field">
                                            <label>Title</label>
                                            <input type="text" wire:model="attachments.{{ $index }}.title"
                                                placeholder="Attachment title" class="cc-input">
                                            @error("attachments.{$index}.title")
                                                <span class="cc-error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="cc-field">
                                            <label>Type</label>
                                            <select wire:model="attachments.{{ $index }}.type" class="cc-input">
                                                <option value="pdf">PDF Document</option>
                                                <option value="video">Video</option>
                                                <option value="image">Image</option>
                                                <option value="other">Other</option>
                                            </select>
                                            @error("attachments.{$index}.type")
                                                <span class="cc-error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline" wire:click="addAttachment"
                            style="margin-top: 1rem; width: auto; display: inline-flex;">
                            + Add another attachment
                        </button>
                    </div>

                    <div class="cc-actions" style="margin-top: 2rem;">
                        <a href="{{ route('cours.show', $cour) }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span wire:loading.remove wire:target="save">Create Chapter →</span>
                            <span wire:loading wire:target="save">Creating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
