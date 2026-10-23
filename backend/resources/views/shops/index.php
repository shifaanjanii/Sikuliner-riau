<section class="shop-directory-page">
    <div class="container">
        <div class="shop-directory-heading reveal">
            <span class="eyebrow">DIREKTORI TOKO RIAU</span>
            <h1>Cari toko kuliner<br>berdasarkan kotanya.</h1>
            <p>Pilih kabupaten atau kota terlebih dahulu. Setelah dipilih, seluruh toko dan rumah makan di daerah tersebut akan ditampilkan di bawah.</p>
        </div>

        <form method="get" action="<?= $e($ui->url('shops.browse')) ?>#daftar-toko" class="shop-region-picker" data-filter-form>
            <label for="shop-region-filter">Lokasi kabupaten/kota</label>
            <div class="shop-picker-row"><div class="input-wrap"><?= $ui->icon('pin') ?><select id="shop-region-filter" name="<?= $e($ui->queryName('region')) ?>" required><option value="">Pilih lokasi untuk melihat toko</option><?php foreach ($d['regions'] as $region): ?><option value="<?= $e($region['id']) ?>" <?= (string) $d['region'] === (string) $region['id'] ? 'selected' : '' ?>><?= $e($region['name']) ?></option><?php endforeach; ?></select></div><button class="button button-primary" type="submit">Tampilkan Toko <?= $ui->icon('arrow') ?></button></div>
        </form>

        <section id="daftar-toko" class="shop-results" aria-live="polite">
            <?php if ($d['region'] === ''): ?>
                <div class="shop-selection-state"><span class="empty-icon"><?= $ui->icon('pin') ?></span><h2>Pilih lokasi dahulu</h2><p>Daftar toko tidak ditampilkan sebelum kamu memilih kabupaten atau kota. Ini membantu kamu langsung melihat semua tempat makan yang relevan.</p></div>
            <?php elseif ($d['shops']): ?>
                <div class="shop-results-heading"><div><span class="eyebrow">TOKO DI <?= $e(strtoupper($d['selected_region']['name'] ?? 'DAERAH PILIHAN')) ?></span><h2><?= count($d['shops']) ?> toko siap dijelajahi</h2></div><a class="reset-link" href="<?= $e($ui->url('shops.browse')) ?>#daftar-toko">Ganti lokasi</a></div>
                <div class="shop-grid"><?php foreach ($d['shops'] as $shop): ?><?php $shopUrl = $ui->url('shops.show', ['id' => $shop['id']], ['region' => $d['region']]); ?><article class="shop-card reveal"><a class="shop-card-image" href="<?= $e($shopUrl) ?>" tabindex="-1" aria-hidden="true"><img class="<?= !empty($shop['photo']) ? 'is-photo' : '' ?>" src="<?= $e($ui->photo($shop['photo'] ?? null)) ?>" alt="" width="680" height="440" loading="lazy" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"></a><div class="shop-card-body"><div class="food-origin"><?= $ui->icon('pin') ?><span><?= $e($shop['region_name']) ?></span></div><h2><a href="<?= $e($shopUrl) ?>"><?= $e($shop['name']) ?></a></h2><p class="shop-address"><?= $e($shop['alamat']) ?></p><?php if (!empty($shop['jam_buka'])): ?><p class="shop-hours"><?= $ui->icon('info') ?><?= $e($shop['jam_buka']) ?></p><?php endif; ?><div class="shop-menu-preview"><span>Menu tersedia</span><p><?php $menuNames = array_column($shop['menus'] ?? [], 'name'); echo $e($menuNames ? implode(', ', array_slice($menuNames, 0, 3)) : 'Belum dicantumkan'); ?></p></div><a class="text-link" href="<?= $e($shopUrl) ?>">Lihat profil &amp; harga menu <?= $ui->icon('arrow') ?></a></div></article><?php endforeach; ?></div>
            <?php else: ?>
                <div class="shop-selection-state"><span class="empty-icon"><?= $ui->icon('search') ?></span><h2>Belum ada toko di <?= $e($d['selected_region']['name'] ?? 'lokasi ini') ?>.</h2><p>Silakan pilih lokasi lain atau tambahkan data toko melalui panel admin.</p></div>
            <?php endif; ?>
        </section>
    </div>
</section>
