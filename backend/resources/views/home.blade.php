@extends('layouts.app')

@section('title', 'SiKuliner Riau — Jelajahi Cita Rasa Riau')

@section('content')
    <section class="hero" id="beranda">
        <div>
            <span class="label">Dari Riau, penuh cita rasa</span>

            <h1>
                Kenali daerahnya.<br>
                <span>Jelajahi rasanya.</span>
            </h1>

            <p>
                Temukan cerita di balik kuliner khas Riau.
                Mulai perjalananmu mengenal makanan tradisional
                dari berbagai kabupaten dan kota.
            </p>

            <a href="#tentang" class="button">
                Kenali SiKuliner ↗
            </a>
        </div>

        <div class="feature">
            <div class="number">12</div>
            <h2>Kabupaten &amp; Kota</h2>

            <p>
                Beragam daerah, cerita, dan tradisi kuliner
                untuk kamu kenali lebih dekat.
            </p>
        </div>
    </section>

    <section class="about" id="tentang">
        <h2>Kenalan dengan SiKuliner Riau</h2>

        <div class="cards">
            <article class="card">
                <h3>Jelajahi daerah</h3>
                <p>
                    Kenali kekayaan kuliner dari berbagai
                    kabupaten dan kota di Provinsi Riau.
                </p>
            </article>

            <article class="card">
                <h3>Temukan kuliner</h3>
                <p>
                    Mengenal ragam makanan utama, kue tradisional,
                    minuman, dan oleh-oleh khas daerah.
                </p>
            </article>

            <article class="card">
                <h3>Kenali ceritanya</h3>
                <p>
                    Pelajari deskripsi dan cerita budaya
                    yang menyertai makanan khas Riau.
                </p>
            </article>
        </div>
    </section>
@endsection
