<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

{{-- ========== NAV ========== --}}
<header class="nav-wrap">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
            edu<span>me</span>x
        </a>

        <nav class="nav-links">
            <a href="#">Courses</a>
            @auth
                <a href="#">Dashboard</a>
            @endauth
            <a href="#">About</a>
        </nav>

        <div class="nav-actions">
            @auth
                <a href="#" class="btn btn-primary btn-sm-nav">Dashboard →</a>
                <form method="POST" action="#" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">Sign Up Free</a>
            @endguest
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
            <a href="#">Dashboard</a>
        @endauth
        <a href="#">About</a>
        @auth
            <a href="#" class="btn btn-primary" style="margin-top:0.5rem;">Dashboard →</a>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary" style="margin-top:0.5rem;">Sign Up Free</a>
        @endguest
    </div>
</header>
