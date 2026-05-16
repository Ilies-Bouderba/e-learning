<div class="enroll-page">
    <div class="container">
        <div class="enroll-card">
            <div class="enroll-icon">{{ $cour->icon }}</div>
            <h1 class="enroll-title">{{ $cour->title }}</h1>
            <p class="enroll-dept">{{ $cour->department->icon }} {{ $cour->department->name }}</p>
            <p class="enroll-desc">{{ $cour->description }}</p>
            <div class="enroll-meta">
                <span>👨‍🏫 {{ $cour->teacher->name }}</span>
                <span>📖 {{ $cour->chapters()->count() }} chapters</span>
                <span>👥 {{ $cour->enrollments()->count() }} students</span>
            </div>
            @if ($cour->hasPassword())
                <div class="enroll-form">
                    <p class="enroll-locked">🔒 This course requires a password to enroll.</p>
                    <div class="cc-field">
                        <label class="cc-label">Course Password</label>
                        <input type="password" class="cc-input" wire:model="password" placeholder="Enter password...">
                        @error('password')
                            <span class="cc-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="enroll-actions">
                {{-- FIXED: changed dashboard.student to student.dashboard --}}
                <a href="{{ route('student.dashboard') }}" class="btn btn-ghost">← Back</a>
                <button class="btn btn-primary btn-lg" wire:click="enroll">
                    <span wire:loading.remove wire:target="enroll">Enroll Now →</span>
                    <span wire:loading wire:target="enroll">Enrolling...</span>
                </button>
            </div>
        </div>
    </div>
</div>
