@extends('layouts.auth')
@section('title', 'Dashboard admin — SiKuliner Riau')
@section('content')
<div class="admin-layout">
    <aside class="panel admin-sidebar">
        <p class="eyebrow">ADMINISTRATOR</p>
        <nav aria-label="Menu admin">
            <a href="{{ route('admin.dashboard') }}" aria-current="page">Dashboard</a>
            <a href="{{ route('account') }}">Akun saya</a>
            <a href="{{ route('home') }}">Lihat beranda</a>
        </nav>
    </aside>
    <section class="panel">
        <h1>Selamat datang, {{ auth()->user()->name }}.</h1>
        <p>Kamu masuk sebagai administrator SiKuliner Riau.</p>
        <p class="muted">Modul pengelolaan wilayah dan kuliner akan tersedia pada pengembangan berikutnya.</p>
    </section>
</div>
@endsection
