{{-- resources/views/livewire/cours/manage-cours.blade.php --}}

<div class="manage-cours-page">
    <div class="container">

        {{-- Header --}}
        <div class="mc-header">
            <div>
                <span class="section-tag">Teacher Panel</span>
                <h1 class="cc-title">My Courses</h1>
                <p class="cc-sub">Manage, edit or delete your courses.</p>
            </div>
            <a href="{{ route('cours.create') }}" class="btn btn-primary">+ New Course</a>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        {{-- Search --}}
        <div class="mc-search-wrap">
            <input
                type="text"
                class="mc-search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search courses..."
            >
        </div>

        {{-- Delete confirm modal --}}
        @if($deletingId)
        <div class="mc-modal-overlay">
            <div class="mc-modal">
                <div class="mc-modal-icon">🗑️</div>
                <h3 class="mc-modal-title">Delete this course?</h3>
                <p class="mc-modal-sub">This action cannot be undone. All course data will be lost.</p>
                <div class="mc-modal-actions">
                    <button class="btn btn-ghost" wire:click="cancelDelete">Cancel</button>
                    <button class="btn btn-danger" wire:click="delete">
                        <span wire:loading.remove wire:target="delete">Yes, Delete</span>
                        <span wire:loading wire:target="delete">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Courses table --}}
        @if($courses->isEmpty())
            <div class="mc-empty">
                <span>📭</span>
                <p>No courses found. <a href="{{ route('cours.create') }}">Create one →</a></p>
            </div>
        @else
        <div class="mc-table-wrap">
            <table class="mc-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr class="mc-row">
                        <td>
                            <div class="mc-course-name">
                                <span class="mc-course-icon">{{ $course->icon }}</span>
                                <span>{{ $course->title }}</span>
                            </div>
                        </td>
                        <td class="mc-desc">{{ Str::limit($course->description, 60) ?: '—' }}</td>
                        <td class="mc-date">{{ $course->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="mc-actions">
                                <a href="{{ route('cours.edit', $course) }}" class="mc-btn-edit">Edit</a>
                                <button class="mc-btn-delete" wire:click="confirmDelete({{ $course->id }})">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mc-pagination">
            {{ $courses->links() }}
        </div>
        @endif

    </div>
</div>
