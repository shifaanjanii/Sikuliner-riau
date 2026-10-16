@extends('layouts.auth')
@section('title', 'Daftar — SiKuliner Riau')
@section('content')
<div class="auth-grid">
    <section class="intro">
        <p class="eyebrow">KENALI RASA RIAU</p>
        <h1>Satu akun, awal perjalananmu.</h1>
        <p>Buat akun pengguna untuk bergabung dengan SiKuliner Riau.</p>
        <a class="text-link" href="{{ route('home') }}">Kembali ke beranda &rarr;</a>
    </section>
    <section class="panel" aria-labelledby="form-title">
        <h2 id="form-title">Buat akun</h2>
        <form method="post" action="{{ route('register.store') }}" data-submit-form>
            @csrf
            <label for="name">Nama</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="100" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
            @error('name')<p class="field-error" id="name-error" role="alert">{{ $message }}</p>@enderror
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" maxlength="255" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')<p class="field-error" id="email-error" role="alert">{{ $message }}</p>@enderror
            <label for="password">Password</label>
            <p class="hint" id="password-hint">Minimal 8 karakter, mengandung huruf dan angka.</p>
            <div class="password-field">
                <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="128" required aria-describedby="password-hint @error('password') password-error @enderror" @error('password') aria-invalid="true" @enderror>
                <button type="button" class="password-toggle" data-toggle-password="password" aria-controls="password" aria-pressed="false">Lihat</button>
            </div>
            @error('password')<p class="field-error" id="password-error" role="alert">{{ $message }}</p>@enderror
            <label for="password_confirmation">Konfirmasi password</label>
            <div class="password-field">
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" maxlength="128" required>
                <button type="button" class="password-toggle" data-toggle-password="password_confirmation" aria-controls="password_confirmation" aria-pressed="false">Lihat</button>
            </div>
            <button class="button button-wide" type="submit" data-submit-button data-loading-label="Membuat akun…">Daftar</button>
        </form>
        <p class="form-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </section>
</div>
@endsection
