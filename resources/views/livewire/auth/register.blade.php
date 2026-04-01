{{-- resources/views/livewire/auth/register.blade.php --}}

<div class="auth-page">

    <div class="auth-left">

        <div class="auth-left-content">
            <div class="auth-quote">
                "Education is the passport to the future, for tomorrow belongs to those who prepare for it today."
            </div>
            <div class="auth-quote-author">— Malcolm X</div>

            <div class="auth-left-stats">
                <div class="auth-stat">
                    <span class="auth-stat-num">120+</span>
                    <span class="auth-stat-label">Courses</span>
                </div>
                <div class="auth-stat">
                    <span class="auth-stat-num">3.4k</span>
                    <span class="auth-stat-label">Students</span>
                </div>
                <div class="auth-stat">
                    <span class="auth-stat-num">48</span>
                    <span class="auth-stat-label">Teachers</span>
                </div>
            </div>
        </div>

        <div class="auth-deco-circle"></div>
        <div class="auth-deco-sq"></div>
        <div class="auth-deco-dot"></div>
    </div>

    <div class="auth-right">
        <div class="auth-form-wrap">

            <div class="auth-form-header">
                <span class="auth-tag">Get started</span>
                <h1 class="auth-title">Create your account</h1>
                <p class="auth-sub">Already have an account? <a href="/login">Log in →</a></p>
            </div>

            <form class="auth-form" wire:submit="register">

                <div class="form-group">
                    <label for="name">Full name</label>
                    <input type="text" id="name" wire:model="name" placeholder="Sara Ahmed" autocomplete="name">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" wire:model="email" placeholder="you@example.com"
                        autocomplete="email">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role">I am a</label>

                    <div class="role-picker">

                        <label class="role-option {{ $role == 'student' ? 'role-active' : '' }}">
                            <input type="radio" wire:model.live="role" value="student">
                            <span class="role-icon">🎓</span>
                            <span class="role-label">Student</span>
                        </label>

                        <label class="role-option {{ $role == 'teacher' ? 'role-active' : '' }}">
                            <input type="radio" wire:model.live="role" value="teacher">
                            <span class="role-icon">📖</span>
                            <span class="role-label">Teacher</span>
                        </label>

                    </div>

                    @error('role')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" wire:model="password" placeholder="Min. 8 characters"
                        autocomplete="new-password">
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation"
                        placeholder="••••••••" autocomplete="new-password">
                </div>

                <div class="form-check">
                    <input type="checkbox" id="terms" wire:model="terms">
                    <label for="terms">
                        I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>

                @error('terms')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <button type="submit" class="btn btn-primary btn-full">
                    Create Account →
                </button>

            </form>

            <div class="auth-divider">
                <span>or continue with</span>
            </div>

            <div class="auth-socials">

                <button type="button" class="social-btn">
                    Google
                </button>

                <button type="button" class="social-btn">
                    GitHub
                </button>

            </div>

        </div>
    </div>

</div>
