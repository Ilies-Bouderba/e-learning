<div class="dash-layout">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="sidebar-logo">edu<span>me</span>x</a>
        <nav class="sidebar-nav">
            <span class="sidebar-nav-label">Overview</span>
            {{-- FIXED: changed from dashboard.admin to admin.dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><span class="sidebar-icon">🏠</span>
                Dashboard</a>
            <span class="sidebar-nav-label">Manage</span>
            <a href="{{ route('admin.teachers') }}" class="sidebar-link"><span class="sidebar-icon">👨‍🏫</span>
                Teachers</a>
            <a href="{{ route('admin.students') }}" class="sidebar-link"><span class="sidebar-icon">🎓</span>
                Students</a>
            <a href="{{ route('admin.departments') }}" class="sidebar-link"><span class="sidebar-icon">🏛️</span>
                Departments</a>
            {{-- REMOVED any links to courses or quizzes --}}
        </nav>
        <div class="sidebar-user">
            <div class="sidebar-avatar">AD</div>
            <div class="sidebar-user-info"><span class="sidebar-user-name">{{ auth()->user()->name }}</span><span
                    class="sidebar-user-role">Admin</span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sidebar-logout"
                    title="Logout">↩</button></form>
        </div>
    </aside>
    <main class="dash-main">
        <div class="dash-header">
            <div><span class="section-tag">Admin Panel</span>
                <h1 class="dash-title">Departments</h1>
                <p class="dash-subtitle">Organize courses into departments.</p>
            </div>
            <button class="btn btn-primary" wire:click="openCreate">+ Add Department</button>
        </div>

        @if (session('success'))
            <div class="mc-flash">{{ session('success') }}</div>
        @endif

        @if ($showForm)
            <div class="mc-modal-overlay">
                <div class="mc-modal admin-modal">
                    <h3 class="mc-modal-title">{{ $editingId ? 'Edit Department' : 'Add Department' }}</h3>
                    <form wire:submit="save" class="admin-form">
                        <div class="cc-field">
                            <label class="cc-label">Icon</label>
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
                        <div class="cc-field"><label class="cc-label">Name</label><input type="text" class="cc-input"
                                wire:model="name" placeholder="e.g. Mathematics">
                            @error('name')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="cc-field"><label class="cc-label">Description <span
                                    class="cc-optional">(optional)</span></label><input type="text" class="cc-input"
                                wire:model="description" placeholder="Short description">
                            @error('description')
                                <span class="cc-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mc-modal-actions" style="margin-top:1.5rem;">
                            <button type="button" class="btn btn-ghost"
                                wire:click="$set('showForm',false)">Cancel</button>
                            <button type="submit" class="btn btn-primary"><span wire:loading.remove
                                    wire:target="save">{{ $editingId ? 'Save Changes' : 'Create' }} →</span><span
                                    wire:loading wire:target="save">Saving...</span></button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if ($deletingId)
            <div class="mc-modal-overlay">
                <div class="mc-modal">
                    <div class="mc-modal-icon">🗑️</div>
                    <h3 class="mc-modal-title">Delete this department?</h3>
                    <p class="mc-modal-sub">Courses in this department may be affected.</p>
                    <div class="mc-modal-actions">
                        <button class="btn btn-ghost" wire:click="cancelDelete">Cancel</button>
                        <button class="btn btn-danger" wire:click="delete">Yes, Delete</button>
                    </div>
                </div>
            </div>
        @endif

        <div class="dept-cards">
            @forelse($departments as $dept)
                <div class="dept-card">
                    <div class="dept-card-top">
                        <span class="dept-card-icon">{{ $dept->icon }}</span>
                        <div class="mc-actions">
                            <button class="mc-btn-edit" wire:click="openEdit({{ $dept->id }})">Edit</button>
                            <button class="mc-btn-delete"
                                wire:click="confirmDelete({{ $dept->id }})">Delete</button>
                        </div>
                    </div>
                    <div class="dept-card-name">{{ $dept->name }}</div>
                    <div class="dept-card-desc">{{ $dept->description ?: '—' }}</div>
                    <span class="dept-card-count">{{ $dept->courses_count }} courses</span>
                </div>
            @empty
                <div class="mc-empty"><span>🏛️</span>
                    <p>No departments yet.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
