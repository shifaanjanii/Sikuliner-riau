<article class="food-card">
    <?php $regionName = $ui->regionName($food['region_id'] ?? null); $regionMapUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($regionName . ', Riau'); ?>
    <div class="food-image"><img class="<?= !empty($food['photo']) && strpos($food['photo'], 'images/') !== 0 ? 'is-photo' : '' ?>" src="<?= $e($ui->photo($food['photo'] ?? null)) ?>" alt="<?= $e($food['name']) ?>" width="640" height="480" loading="lazy" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"><span class="category-tag"><?= $e($food['category']) ?></span></div>
    <div class="food-body">
        <div class="food-origin"><?= $ui->icon('pin') ?><span><?= $e($regionName) ?></span></div>
        <h3><?= $e($food['name']) ?></h3>
        <section class="food-story"><h4>Deskripsi</h4><p><?= $e($food['description'] ?? '') ?></p></section>
        <a class="food-map-link" href="<?= $e($regionMapUrl) ?>" target="_blank" rel="noopener noreferrer"><?= $ui->icon('pin') ?><span>Buka lokasi <?= $e($regionName) ?> di Google Maps</span><?= $ui->icon('external') ?></a>
        <section class="food-shops"><h4>Toko dan harga</h4><?php if (!empty($food['shops'])): ?><div class="food-shop-list"><?php foreach ($food['shops'] as $shop): ?><?php $shopMapUrl = !empty($shop['maps_url']) ? $shop['maps_url'] : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($shop['name'] . ', ' . $shop['alamat'] . ', Riau'); ?><div class="food-shop-row"><div><strong><?= $e($shop['name']) ?></strong><span><?= $e($shop['alamat']) ?></span><?php if ($shop['harga'] !== null): ?><b>Rp <?= number_format((float) $shop['harga'], 0, ',', '.') ?></b><?php endif; ?></div><a class="shop-map-button" href="<?= $e($shopMapUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Buka lokasi <?= $e($shop['name']) ?> di peta" title="Buka di peta"><?= $ui->icon('pin') ?></a></div><?php endforeach; ?></div><?php else: ?><p class="food-shop-empty">Data toko dan harga belum tersedia.</p><?php endif; ?></section>
    </div>
</article>
