<?php $featured = $d['featured']; $first = $featured[0] ?? ['name' => 'Kuliner Riau', 'category' => 'Cerita rasa', 'caption' => '', 'photo' => null]; ?>
<section class="hero" data-hero>
    <div class="container hero-inner">
        <div class="hero-copy">
            <span class="hero-label">Rasa lokal, cerita baru.</span>
            <h1><span class="hero-title-line">Kenali Riau,</span><span class="hero-title-line">lewat <em>rasanya.</em></span></h1>
            <p class="hero-description">Jelajahi makanan khas dari berbagai daerah di Riau dan kenali cerita di balik rasanya.</p>
            <a class="button button-primary" href="#jelajah">Mulai Jelajah <?= $ui->icon('arrow') ?></a>
            <p class="hero-support">12 kabupaten dan kota. Banyak rasa untuk dikenal.</p>
        </div>
        <div class="hero-art" aria-label="Pilihan ilustrasi makanan Riau">
            <div class="hero-circle" aria-hidden="true"></div><div class="hero-orbit" aria-hidden="true"></div>
            <div class="plate-main" data-main-plate><div class="plate-depth"><img src="<?= $e($ui->photo($first['photo'])) ?>" alt="Ilustrasi <?= $e($first['name']) ?>" width="800" height="800" fetchpriority="high" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"></div></div>
            <?php foreach (array_slice($featured, 1, 4) as $index => $item): ?>
            <div class="plate-small plate-small-<?= $index + 1 ?>" style="--plate-order:<?= $index ?>"><div class="plate-depth"><img src="<?= $e($ui->photo($item['photo'])) ?>" alt="Ilustrasi <?= $e($item['name']) ?>" width="300" height="300" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"></div></div>
            <?php endforeach; ?>
            <div class="hero-food-label">
                <div aria-live="polite" aria-atomic="true"><span class="sr-only" data-featured-category><?= $e($first['category']) ?></span><h2 data-featured-name><?= $e($first['name']) ?></h2><p class="sr-only" data-featured-caption><?= $e($first['caption']) ?></p></div>
                <?php if (count($featured) > 1): ?>
                <div class="carousel-dots" aria-label="Pilih makanan unggulan"><?php foreach ($featured as $index => $item): ?><button type="button" data-slide-dot="<?= $index ?>" class="<?= $index === 0 ? 'is-active' : '' ?>" aria-label="Tampilkan <?= $e($item['name']) ?>" aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>"></button><?php endforeach; ?></div>
                <?php endif; ?>
            </div>
            <?php if (count($featured) > 1): ?><div class="carousel-buttons"><button class="icon-button" type="button" data-hero-prev aria-label="Makanan unggulan sebelumnya"><?= $ui->icon('chevron') ?></button><button class="icon-button" type="button" data-hero-next aria-label="Makanan unggulan berikutnya"><?= $ui->icon('chevron') ?></button></div><?php endif; ?>
            <div class="hero-art-note"><span class="hero-credit">Ilustrasi kuliner · gambar AI</span><button class="hero-autoplay" type="button" data-hero-autoplay aria-pressed="false" hidden>Jeda animasi</button></div>
        </div>
    </div>
    <script type="application/json" data-featured-data><?= json_encode(array_map(static function ($item) use ($ui) { $item['photo'] = $ui->photo($item['photo']); return $item; }, $featured), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
    <div class="taste-ribbon"><div class="container"><span>Kenali rasanya</span><i aria-hidden="true">◆</i><span>Temukan ceritanya</span><i aria-hidden="true">◆</i><span>Jelajahi Riau</span></div></div>
</section>
<?php $ui->partial('components.catalog', ['standalone' => false]); ?>
<section class="story-section reveal"><div class="container story-strip"><h2>Mengenal Riau<br>lewat kuliner.</h2><div><p>Jelajahi makanan khas, kenali daerah asalnya, dan temukan cerita di balik setiap rasa.</p><a class="text-link" href="<?= $e($ui->url('about')) ?>">Tentang SiKuliner Riau <?= $ui->icon('arrow') ?></a></div></div></section>
