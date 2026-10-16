<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SiKuliner Riau')</title>
    <link rel="stylesheet" href="{{ asset('css/sprint1.css') }}">
</head>
<body>
    <header>
        <nav class="container" aria-label="Navigasi utama">
            <a href="{{ url('/') }}" class="logo">SiKuliner<span> Riau.</span></a>
            <div class="nav-links">
                <a href="{{ url('/') }}#beranda">Beranda</a>
                <a href="{{ url('/') }}#tentang">Tentang SiKuliner</a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('account') }}">Akun saya</a>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="button button-small" type="submit">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}">Daftar</a>
                @endauth
            </div>
        </nav>
    </header>
    <main class="container">@yield('content')</main>
    <footer>
        <div class="container">&copy; {{ date('Y') }} SiKuliner Riau. Jelajahi cita rasa, kenali budaya.</div>
    </footer>
</body>
</html>
