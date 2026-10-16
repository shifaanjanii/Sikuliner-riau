@extends('layouts.auth')
@section('title', 'Akun saya — SiKuliner Riau')
@section('content')
<section class="panel account-panel">
    <p class="eyebrow">AKUN SAYA</p>
    <h1>Halo, {{ auth()->user()->name }}.</h1>
    <p>Kamu sudah masuk ke SiKuliner Riau.</p>
    <dl class="profile-details">
        <dt>Email</dt><dd>{{ auth()->user()->email }}</dd>
        <dt>Akses</dt><dd>{{ auth()->user()->isAdmin() ? 'Administrator' : 'Pengguna / Wisatawan' }}</dd>
    </dl>
    <a class="button" href="{{ route('home') }}">Kembali ke beranda</a>
</section>
@endsection
