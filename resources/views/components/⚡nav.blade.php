<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }
};
?>

{{-- ========== NAV ========== --}}
<header class="nav-wrap">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
            edu<span>me</span>x
        </a>

        <nav class="nav-links">
            @auth
                <a href="{{ route('cours.create') }}">Courses</a>
                <a href="{{ auth()->user()->role === 'teacher' ? route('dashboard.teacher') : route('dashboard.student') }}">Dashboard</a>
            @endauth
            <a href="#">About</a>
        </nav>

        <div class="nav-actions">
            @auth
                <button class="btn btn-ghost" wire:click="logout" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                    Logout
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">Sign Up Free</a>
            @endauth
        </div>

        {{-- Mobile burger --}}
        <button class="nav-burger" id="navBurger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div class="nav-mobile" id="navMobile">
        <a href="#">Courses</a>
            @auth
                <a href="{{ auth()->user()->role === 'teacher' ? route('dashboard.teacher') : route('dashboard.student') }}">Dashboard</a>
            @endauth
        <a href="#">About</a>
        @auth
            <button class="btn btn-primary" wire:click="logout" style="margin-top:0.5rem;">Logout</button>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary" style="margin-top:0.5rem;">Sign Up Free</a>
        @endauth
    </div>
</header>

<script>
    document.getElementById('navBurger').addEventListener('click', () => {
        document.getElementById('navMobile').classList.toggle('open');
    });
</script>
