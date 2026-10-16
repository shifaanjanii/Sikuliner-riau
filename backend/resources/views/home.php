<?php $featured = $d['featured']; $first = $featured[0] ?? ['name' => 'Kuliner Riau', 'category' => 'Cerita rasa', 'caption' => '', 'photo' => null]; ?>
<section class="hero" data-hero>
    <div class="container hero-inner">
        <div class="hero-copy">
            <span class="hero-label"><?= $ui->icon('spark') ?> Rasa lokal, cerita baru.</span>
            <h1>Kenali Riau,<br>lewat <em>rasanya.</em></h1>
            <p class="hero-description">Jelajahi makanan khas dari berbagai daerah di Riau dan kenali cerita di balik rasanya.</p>
            <a class="button button-primary" href="#jelajah">Mulai Jelajah <?= $ui->icon('arrow') ?></a>
            <div class="hero-support"><span class="support-mark"><?= $ui->icon('pin') ?></span><p>12 kabupaten dan kota.<br>Banyak rasa untuk dikenal.</p></div>
        </div>
        <div class="hero-art" aria-label="Pilihan ilustrasi makanan Riau">
            <div class="hero-circle" aria-hidden="true"></div><div class="hero-orbit" aria-hidden="true"></div>
            <span class="hero-dot" aria-hidden="true"></span><span class="hero-dot small" aria-hidden="true"></span>
            <div class="plate-main" data-main-plate><img src="<?= $e($ui->photo($first['photo'])) ?>" alt="Ilustrasi <?= $e($first['name']) ?>" width="800" height="800" fetchpriority="high" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"></div>
            <?php foreach (array_slice($featured, 1, 2) as $index => $item): ?>
            <div class="plate-small plate-small-<?= $index === 0 ? 'one' : 'two' ?>"><img src="<?= $e($ui->photo($item['photo'])) ?>" alt="Ilustrasi <?= $e($item['name']) ?>" width="300" height="300" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"></div>
            <?php endforeach; ?>
            <div class="hero-food-label">
                <div aria-live="polite" aria-atomic="true"><span class="eyebrow" data-featured-category><?= $e($first['category']) ?></span><h2 data-featured-name><?= $e($first['name']) ?></h2><p data-featured-caption><?= $e($first['caption']) ?></p></div>
                <?php if (count($featured) > 1): ?>
                <div class="hero-controls"><div class="carousel-dots" aria-hidden="true"><?php foreach ($featured as $index => $item): ?><span data-slide-dot class="<?= $index === 0 ? 'is-active' : '' ?>"></span><?php endforeach; ?></div><div class="carousel-buttons"><button class="icon-button" type="button" data-hero-prev aria-label="Makanan unggulan sebelumnya"><?= $ui->icon('arrow-left') ?></button><button class="icon-button" type="button" data-hero-next aria-label="Makanan unggulan berikutnya"><?= $ui->icon('arrow') ?></button></div></div>
                <?php endif; ?>
            </div>
            <span class="hero-credit">Ilustrasi kuliner · gambar AI</span>
        </div>
    </div>
    <script type="application/json" data-featured-data><?= json_encode(array_map(static function ($item) use ($ui) { $item['photo'] = $ui->photo($item['photo']); return $item; }, $featured), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
    <div class="hero-divider"><div class="container hero-footline"><span><?= $ui->icon('leaf') ?> CERITA RASA DARI BUMI LANCANG KUNING</span><div class="hero-places"><span>Pekanbaru</span><span>Kampar</span><span>Siak</span><span>dan daerah lainnya</span></div></div></div>
</section>
<?php $ui->partial('components.catalog', ['standalone' => false]); ?>
<section class="story-section reveal">
    <div class="container story-strip">
        <div><span class="eyebrow">LEBIH DEKAT DENGAN RIAU</span><h2>Setiap rasa punya<br>cerita untuk dibagi.</h2><p>Dari hidangan rumahan sampai kudapan tradisional, kenali Riau melalui makanan dan cerita yang menyertainya.</p><a class="text-link" href="<?= $e($ui->url('about')) ?>">Kenalan dengan SiKuliner <?= $ui->icon('arrow') ?></a></div>
        <div class="story-points">
            <div class="story-point"><span><?= $ui->icon('pin') ?></span><div><h3>Jelajahi daerahnya</h3><p>Pilih kabupaten atau kota yang ingin kamu kenali.</p></div></div>
            <div class="story-point"><span><?= $ui->icon('food') ?></span><div><h3>Temukan ragam rasanya</h3><p>Kenali makanan utama, kue tradisional, minuman, dan oleh-oleh.</p></div></div>
            <div class="story-point"><span><?= $ui->icon('book') ?></span><div><h3>Baca cerita di baliknya</h3><p>Lebih dari nama makanan, ada budaya yang bisa dipelajari.</p></div></div>
        </div>
    </div>
</section>
