<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiKuliner Riau')</title>
    <link rel="stylesheet" href="{{ asset('css/sprint1.css') }}">
    <script src="{{ asset('js/sprint1.js') }}" defer></script>
</head>
<body class="account-page">
    <header class="site-header">
        <nav class="container" aria-label="Navigasi utama">
            <a class="logo" href="{{ route('home') }}">SiKuliner <span>Riau.</span></a>
            <div class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('account') }}">Akun saya</a>
                    @endif
                    <form method="post" action="{{ route('logout') }}" data-submit-form>
                        @csrf
                        <button class="button button-small" type="submit" data-submit-button data-loading-label="Keluar…">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}">Daftar</a>
                @endauth
            </div>
        </nav>
    </header>
    <main class="container account-main" id="main-content">
        @if(session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
    <footer class="container site-footer">SiKuliner Riau · Menjelajahi rasa, mengenal budaya.</footer>
</body>
</html>
