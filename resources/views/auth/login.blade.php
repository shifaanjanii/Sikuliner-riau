@extends('layouts.auth')
@section('title', 'Masuk — SiKuliner Riau')
@section('content')
<div class="auth-grid">
    <section class="intro">
        <p class="eyebrow">SELAMAT DATANG KEMBALI</p>
        <h1>Cerita rasa dimulai dari sini.</h1>
        <p>Masuk ke akun SiKuliner Riau untuk melanjutkan perjalananmu.</p>
        <a class="text-link" href="{{ route('home') }}">Kembali ke beranda &rarr;</a>
    </section>
    <section class="panel" aria-labelledby="form-title">
        <h2 id="form-title">Masuk ke akun</h2>
        <p class="muted">Gunakan email dan password yang sudah terdaftar.</p>
        <form method="post" action="{{ route('login.store') }}" data-submit-form>
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" maxlength="255" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')<p class="field-error" id="email-error" role="alert">{{ $message }}</p>@enderror
            <label for="password">Password</label>
            <div class="password-field">
                <input id="password" name="password" type="password" autocomplete="current-password" maxlength="128" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                <button type="button" class="password-toggle" data-toggle-password="password" aria-controls="password" aria-pressed="false">Lihat</button>
            </div>
            @error('password')<p class="field-error" id="password-error" role="alert">{{ $message }}</p>@enderror
            <button class="button button-wide" type="submit" data-submit-button data-loading-label="Memeriksa…">Masuk</button>
        </form>
        <p class="form-footer">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
    </section>
</div>
@endsection
