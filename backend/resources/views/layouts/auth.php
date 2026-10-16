<!doctype html>
<html lang="id">
<head><?php $ui->partial('partials.head', ['title' => $title]); ?></head>
<body class="auth-body">
    <a class="skip-link" href="#konten">Langsung ke form</a>
    <div class="auth-shell">
        <section class="auth-main">
            <?php $ui->partial('partials.brand'); ?>
            <main id="konten" class="auth-content"><?= $content ?></main>
            <a class="back-link" href="<?= $e($ui->url('home')) ?>"><?= $ui->icon('arrow-left') ?> Kembali ke Beranda</a>
        </section>
        <aside class="auth-aside">
            <span class="eyebrow"><?= $ui->icon('spark') ?> Rasa lokal, cerita baru.</span>
            <h2>Satu daerah,<br>beribu cerita rasa.</h2>
            <p>Mulai perjalanan kecilmu mengenal kekayaan kuliner Riau.</p>
            <div class="auth-plate"><img src="<?= $e($ui->photo($d['featured'][0]['photo'] ?? null)) ?>" alt="Ilustrasi gulai ikan patin" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"></div>
            <span class="image-credit">Ilustrasi kuliner · gambar AI</span>
            <div class="auth-aside-bottom"><?= $ui->icon('pin') ?><span>Riau, Indonesia</span><span class="tiny-flower" aria-hidden="true"><?= $ui->icon('spark') ?></span></div>
        </aside>
    </div>
</body>
</html>
