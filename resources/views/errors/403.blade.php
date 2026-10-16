@extends('layouts.auth')
@section('title', 'Akses dibatasi — SiKuliner Riau')
@section('content')
<section class="panel account-panel">
    <p class="eyebrow">403 · AKSES DIBATASI</p>
    <h1>Halaman ini khusus administrator.</h1>
    <p>Akunmu tidak memiliki akses ke halaman tersebut.</p>
    <a class="button" href="{{ route('home') }}">Kembali ke beranda</a>
</section>
@endsection
