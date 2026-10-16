<?php $route = $standalone ? 'catalog' : 'home'; $hasFilters = $d['q'] !== '' || $d['region'] !== ''; ?>
<section id="jelajah" class="catalog-section <?= $standalone ? 'catalog-standalone' : '' ?>">
    <div class="container">
        <div class="section-heading reveal">
            <div><span class="eyebrow"><?= $standalone ? 'JELAJAH KULINER' : 'PERJALANAN RASA DIMULAI DI SINI' ?></span><?php if ($standalone): ?><h1>Mau jelajah ke mana?</h1><?php else: ?><h2>Mau jelajah ke mana?</h2><?php endif; ?><p>Pilih daerah dan temukan makanan khas yang ingin kamu kenali.</p></div>
            <?php if (!$standalone): ?><a class="text-link" href="<?= $e($ui->url('catalog')) ?>">Buka katalog <?= $ui->icon('arrow') ?></a><?php endif; ?>
        </div>
        <?php $ui->partial('components.filter', ['route' => $route]); ?>
        <div class="catalog-meta"><div class="filter-summary"><span><strong><?= (int) $d['total'] ?></strong> makanan <?= $hasFilters ? 'ditemukan' : 'untuk dikenal' ?></span><?php if ($d['region'] !== ''): ?><span class="filter-chip"><?= $ui->icon('pin') ?><?= $e($ui->regionName($d['region'])) ?></span><?php endif; ?><?php if ($d['q'] !== ''): ?><span class="filter-chip">“<?= $e($d['q']) ?>”</span><?php endif; ?></div><?php if ($hasFilters): ?><a class="reset-link" href="<?= $e($ui->url($route)) ?>#jelajah">Reset filter</a><?php endif; ?></div>
        <?php if ($d['foods']): ?>
            <div class="food-grid"><?php foreach ($d['foods'] as $food): ?><?php $ui->partial('components.food-card', ['food' => $food]); ?><?php endforeach; ?></div>
            <?php $ui->partial('components.pagination', ['route' => $route]); ?>
        <?php else: ?>
            <div class="empty-state"><span class="empty-icon"><?= $ui->icon($hasFilters ? 'search' : 'food') ?></span><h3><?= $hasFilters ? 'Rasanya belum ketemu.' : 'Cerita rasa sedang disiapkan.' ?></h3><p><?= $hasFilters ? 'Coba nama makanan lain atau jelajahi semua daerah. Siapa tahu kamu menemukan rasa baru.' : 'Katalog makanan akan hadir di sini. Kembali lagi untuk mengenal ragam kuliner khas Riau.' ?></p><?php if ($hasFilters): ?><a class="button button-outline button-small" href="<?= $e($ui->url($route)) ?>#jelajah">Lihat semua makanan <?= $ui->icon('arrow') ?></a><?php endif; ?></div>
        <?php endif; ?>
    </div>
</section>
