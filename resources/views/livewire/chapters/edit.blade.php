<div class="dash-layout">
    <livewire:course-sidebar :cour="$cour" active="chapters" />

    <main class="dash-main">
        <div class="create-course-page">
            <div class="container" style="max-width: 100%; padding: 0;">
                <div class="cc-header">
                    <a href="{{ route('chapters.show', ['cour' => $cour, 'chapter' => $chapter]) }}" class="cc-back">←
                        Back to Chapter</a>
                    <div>
                        <span class="section-tag">{{ $cour->icon }} {{ $cour->title }}</span>
                        <h1 class="cc-title">Edit Chapter: {{ $chapter->title }}</h1>
                        <p class="cc-sub">Update content and attachments</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mc-flash">{{ session('success') }}</div>
                @endif

                <form wire:submit="save" class="cc-form" style="max-width: 100%;">
                    <div class="cc-field">
                        <label class="cc-label">Chapter Title</label>
                        <input type="text" class="cc-input" wire:model="title">
                        @error('title')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Content</label>
                        <textarea class="cc-input cc-textarea" wire:model="content" rows="10"></textarea>
                        @error('content')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Existing Attachments Section --}}
                    <div class="cc-field">
                        <label class="cc-label">Existing Attachments</label>
                        <div class="attachments-container" style="display: flex; flex-direction: column; gap: 1rem;">
                            @forelse($attachments as $attachment)
                                <div class="attachment-card"
                                    style="border: 1.5px solid rgba(15,14,23,0.15); border-radius: 12px; padding: 1rem; background: #fafaf8;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <strong>{{ $attachment->title }}</strong>
                                            <span
                                                style="margin-left: 0.5rem; font-size: 0.75rem; color: var(--c-muted);">({{ strtoupper($attachment->type) }})</span>
                                        </div>
                                        <div style="display: flex; gap: 0.75rem;">
                                            <a href="{{ asset('storage/' . $attachment->file_path) }}" class="btn-sm"
                                                target="_blank">Download</a>
                                            <button type="button" class="btn-sm btn-danger"
                                                wire:click="deleteAttachment({{ $attachment->id }})"
                                                wire:confirm="Delete this attachment?">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="empty-msg">No attachments yet.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Add New Attachment Section --}}
                    <div class="cc-field">
                        <label class="cc-label">Add New Attachment</label>
                        <div class="new-attachment-card"
                            style="border: 1.5px solid rgba(15,14,23,0.15); border-radius: 12px; padding: 1.25rem; background: #fafaf8;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="cc-field">
                                    <label>File</label>
                                    <input type="file" wire:model="newAttachment" class="cc-input">
                                    @error('newAttachment')
                                        <span class="cc-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="cc-field">
                                    <label>Title</label>
                                    <input type="text" wire:model="attachmentTitle" placeholder="Attachment title"
                                        class="cc-input">
                                    @error('attachmentTitle')
                                        <span class="cc-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="cc-field">
                                    <label>Type</label>
                                    <select wire:model="attachmentType" class="cc-input">
                                        <option value="pdf">PDF</option>
                                        <option value="video">Video</option>
                                        <option value="image">Image</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('attachmentType')
                                        <span class="cc-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline" wire:click="addAttachment"
                                style="margin-top: 1rem;">+ Add Attachment</button>
                        </div>
                    </div>

                    <div class="cc-actions" style="margin-top: 2rem;">
                        <a href="{{ route('chapters.show', ['cour' => $cour, 'chapter' => $chapter]) }}"
                            class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span wire:loading.remove wire:target="save">Save Changes →</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
