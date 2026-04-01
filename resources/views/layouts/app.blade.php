<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Edumex') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire styles --}}
    @livewireStyles

    @stack('styles')
</head>

<body>

    <livewire:nav />
    {{-- ========== MAIN ========== --}}
    <main>
        {{ $slot }}
    </main>

    <livewire:footer />

    {{-- Livewire scripts --}}
    @livewireScripts

    {{-- Mobile nav toggle --}}
    <script>
        const burger = document.getElementById('navBurger');
        const mobile = document.getElementById('navMobile');
        burger.addEventListener('click', () => mobile.classList.toggle('open'));
    </script>

    @stack('scripts')
</body>

</html>
