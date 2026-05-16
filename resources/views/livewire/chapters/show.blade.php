<div class="dash-layout">
    <livewire:course-sidebar :cour="$cour" active="chapters" />

    <main class="dash-main">

        <style>
            .comments-list {
                display: flex;
                flex-direction: column;
                gap: 1.25rem;
            }

            .comment-box {
                background: #fafaf8;
                border-radius: 16px;
                padding: 1rem;
                border: 1px solid rgba(15,14,23,0.06);
                transition: 0.2s ease;
            }

            .comment-box:hover {
                border-color: rgba(15,14,23,0.12);
            }

            .comment-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .comment-user {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .comment-avatar,
            .reply-avatar {
                border-radius: 50%;
                background: var(--c-dark);
                color: var(--c-yellow);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                flex-shrink: 0;
            }

            .comment-avatar {
                width: 42px;
                height: 42px;
                font-size: 0.85rem;
            }

            .reply-avatar {
                width: 34px;
                height: 34px;
                font-size: 0.7rem;
            }

            .comment-name {
                font-weight: 700;
                font-size: 0.92rem;
                color: var(--c-dark);
            }

            .comment-date {
                font-size: 0.72rem;
                color: var(--c-muted);
                margin-top: 0.1rem;
            }

            .comment-text {
                margin-top: 0.9rem;
                margin-left: 3.5rem;
                line-height: 1.7;
                font-size: 0.95rem;
                color: var(--c-dark);
                word-break: break-word;
            }

            .reply-text {
                margin-top: 0.5rem;
                margin-left: 2.7rem;
                line-height: 1.6;
                font-size: 0.88rem;
                color: var(--c-dark);
                word-break: break-word;
            }

            .comment-actions {
                margin-top: 0.85rem;
                margin-left: 3.5rem;
            }

            .reply-btn {
                background: transparent;
                border: none;
                color: var(--c-muted);
                font-size: 0.82rem;
                font-weight: 600;
                cursor: pointer;
                transition: 0.2s ease;
                padding: 0;
            }

            .reply-btn:hover {
                color: var(--c-dark);
            }

            .reply-form {
                margin-top: 1rem;
                margin-left: 3.5rem;
            }

            .reply-form textarea {
                width: 100%;
                padding: 0.8rem;
                border-radius: 10px;
                border: 1px solid rgba(15,14,23,0.12);
                resize: vertical;
                font-size: 0.9rem;
                background: white;
            }

            .reply-form textarea:focus {
                outline: none;
                border-color: var(--c-yellow);
            }

            .reply-form-actions {
                margin-top: 0.6rem;
                display: flex;
                gap: 0.5rem;
            }

            .replies-wrapper {
                margin-top: 1rem;
                margin-left: 3.5rem;
                padding-left: 1rem;
                border-left: 2px solid rgba(255,225,77,0.4);

                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .reply-box {
                background: white;
                border-radius: 12px;
                padding: 0.85rem;
                border: 1px solid rgba(15,14,23,0.05);
            }

            .discussion-input {
                width: 100%;
                padding: 1rem;
                border-radius: 12px;
                border: 1px solid rgba(15,14,23,0.1);
                resize: vertical;
                font-size: 0.95rem;
                background: white;
            }

            .discussion-input:focus {
                outline: none;
                border-color: var(--c-yellow);
            }

            .discussion-create {
                margin-bottom: 2rem;
                padding-bottom: 1.5rem;
                border-bottom: 1px solid rgba(15,14,23,0.08);
            }

            .mc-empty {
                text-align: center;
                padding: 3rem 1rem;
                color: var(--c-muted);
            }

            .mc-empty span {
                font-size: 2rem;
                display: block;
                margin-bottom: 0.5rem;
            }
        </style>

        <div class="course-show-header">
            <div class="csh-left">
                <div class="csh-icon">{{ $cour->icon }}</div>

                <div>
                    <div class="csh-dept">
                        {{ $cour->department->icon }}
                        {{ $cour->department->name }}
                    </div>

                    <h1 class="csh-title">
                        {{ $chapter->title }}
                    </h1>

                    <p class="csh-desc">
                        Chapter {{ $chapter->chapter_number }}
                        of {{ $cour->title }}
                    </p>
                </div>
            </div>

            @if (auth()->user()->isTeacher() && $cour->teacher_id == auth()->id())
                <div class="csh-actions">
                    <a href="{{ route('teacher.chapters.edit', ['cour' => $cour, 'chapter' => $chapter]) }}"
                        class="btn btn-primary">
                        Edit Chapter
                    </a>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="mc-flash">
                {{ session('success') }}
            </div>
        @endif

        <div class="dash-card dash-card-wide">

            <div class="chapter-content" style="padding: 1rem 0; line-height: 1.8;">
                {!! nl2br(e($chapter->content)) !!}
            </div>

            @if ($chapter->attachments && $chapter->attachments->count() > 0)

                <div class="dash-card-header" style="margin-top: 2rem;">
                    <h2 class="dash-card-title">
                        📎 Attachments
                    </h2>
                </div>

                <div class="attach-list">

                    @foreach ($chapter->attachments as $attachment)

                        <div class="attach-item">

                            <div class="attach-type attach-{{ $attachment->type }}">
                                {{ strtoupper($attachment->type) }}
                            </div>

                            <div class="attach-info">
                                <div class="attach-name">
                                    {{ $attachment->title }}
                                </div>

                                <div class="attach-course" style="font-size: 0.7rem;">
                                    Uploaded
                                    {{ $attachment->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <a href="{{ asset('storage/' . $attachment->file_path) }}"
                                class="attach-dl"
                                target="_blank"
                                download>
                                ↓
                            </a>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

        <!-- DISCUSSION -->
        <div class="dash-card" style="margin-top: 2rem;">

            <div class="dash-card-header">
                <h2 class="dash-card-title">
                    💬 Discussion ({{ $comments->count() }} comments)
                </h2>
            </div>

            <!-- CREATE COMMENT -->
            @auth
                @if(auth()->user()->isStudent())

                    <div class="discussion-create">

                        <textarea
                            wire:model="newComment"
                            rows="3"
                            class="discussion-input"
                            placeholder="Ask a question or share your thoughts..."
                        ></textarea>

                        @error('newComment')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror

                        <button
                            wire:click="addComment"
                            class="btn btn-primary"
                            style="margin-top: 0.9rem;"
                        >
                            Post Comment →
                        </button>

                    </div>

                @endif
            @endauth

            <!-- COMMENTS -->
            <div class="comments-list">

                @forelse($comments as $comment)

                    <div class="comment-box">

                        <!-- HEADER -->
                        <div class="comment-header">

                            <div class="comment-user">

                                <div class="comment-avatar">
                                    {{ strtoupper(substr($comment->student->name, 0, 2)) }}
                                </div>

                                <div>
                                    <div class="comment-name">
                                        {{ $comment->student->name }}
                                    </div>

                                    <div class="comment-date">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- TEXT -->
                        <div class="comment-text">
                            {{ $comment->comment_text }}
                        </div>

                        <!-- ACTIONS -->
                        @auth
                            @if(auth()->user()->isStudent())

                                <div class="comment-actions">

                                    <button
                                        wire:click="toggleReplyForm({{ $comment->id }})"
                                        class="reply-btn"
                                    >
                                        Reply
                                    </button>

                                </div>

                            @endif
                        @endauth

                        <!-- REPLY FORM -->
                        @if($showReplyForm[$comment->id] ?? false)

                            <div class="reply-form">

                                <textarea
                                    wire:model="replyText.{{ $comment->id }}"
                                    rows="2"
                                    placeholder="Write your reply..."
                                ></textarea>

                                @error("replyText.{$comment->id}")
                                    <span class="cc-error">{{ $message }}</span>
                                @enderror

                                <div class="reply-form-actions">

                                    <button
                                        wire:click="addReply({{ $comment->id }})"
                                        class="btn-sm btn-primary"
                                    >
                                        Post Reply
                                    </button>

                                    <button
                                        wire:click="toggleReplyForm({{ $comment->id }})"
                                        class="btn-sm btn-ghost"
                                    >
                                        Cancel
                                    </button>

                                </div>

                            </div>

                        @endif

                        <!-- REPLIES -->
                        @if($comment->replies && $comment->replies->count() > 0)

                            <div class="replies-wrapper">

                                @foreach($comment->replies as $reply)

                                    <div class="reply-box">

                                        <div class="comment-header">

                                            <div class="comment-user">

                                                <div class="reply-avatar">
                                                    {{ strtoupper(substr($reply->student->name, 0, 2)) }}
                                                </div>

                                                <div>

                                                    <div class="comment-name">
                                                        {{ $reply->student->name }}
                                                    </div>

                                                    <div class="comment-date">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="reply-text">
                                            {{ $reply->reply_text }}
                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    </div>

                @empty

                    <div class="mc-empty">
                        <span>💬</span>
                        <p>No comments yet. Start the discussion!</p>
                    </div>

                @endforelse

            </div>

        </div>

        <div class="dash-card" style="margin-top: 1.5rem;">
            <a href="{{ route('cours.show', $cour) }}" class="btn btn-ghost">
                ← Back to Course
            </a>
        </div>

    </main>
</div>
