<article class="container detail-page">
    <a class="back-link" href="<?= $e($ui->url('catalog', [], ['q' => $d['q'], 'region' => $d['region'], 'page' => $d['page']])) ?>#jelajah"><?= $ui->icon('arrow-left') ?> Kembali ke Jelajah Kuliner</a>
    <div class="detail-grid">
        <div><img class="detail-photo <?= !empty($food['photo']) && strpos($food['photo'], 'images/') !== 0 ? 'is-photo' : '' ?>" src="<?= $e($ui->photo($food['photo'] ?? null)) ?>" alt="<?= $e($food['name']) ?>" width="800" height="800" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>"><?php if (!empty($food['photo']) && strpos($food['photo'], 'images/') === 0): ?><p class="detail-credit">Ilustrasi kuliner · gambar AI</p><?php endif; ?></div>
        <div class="detail-info"><span class="category-tag"><?= $e($food['category']) ?></span><h1><?= $e($food['name']) ?></h1><div class="food-origin"><?= $ui->icon('pin') ?><?= $e($ui->regionName($food['region_id'] ?? null)) ?></div><h2 style="font-size:20px">Cerita di balik rasanya</h2><div class="detail-description"><?= $e($food['description'] ?? 'Cerita makanan ini belum tersedia.') ?></div><?php if (!empty($food['source']) && preg_match('~^https://~', $food['source'])): ?><a class="source-link" href="<?= $e($food['source']) ?>" target="_blank" rel="noopener noreferrer">Baca sumber informasi <?= $ui->icon('external') ?></a><?php endif; ?></div>
    </div>
    <?php if (!empty($food['shops'])): ?>
    <section class="detail-shops" style="margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--border-color, #e5e7eb);">
        <h2 style="font-size: 22px; margin-bottom: 8px;"><?= $ui->icon('pin') ?> Tempat Mencicipi & Toko Penjual</h2>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">Kunjungi langsung toko atau rumah makan terverifikasi berikut untuk menikmati sajian ini:</p>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            <?php foreach ($food['shops'] as $shop): ?>
                <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 17px; font-weight: 600; margin: 0 0 6px;"><?= $e($shop['name']) ?></h3>
                    <?php if (!empty($shop['region_name'])): ?>
                        <div style="font-size: 13px; color: #059669; font-weight: 500; margin-bottom: 8px;"><?= $e($shop['region_name']) ?></div>
                    <?php endif; ?>
                    <p style="font-size: 14px; color: #4b5563; margin: 0 0 8px;"><?= $e($shop['alamat']) ?></p>
                    <?php if (!empty($shop['jam_buka'])): ?>
                        <p style="font-size: 13px; color: #6b7280; margin: 0 0 4px;">🕒 <?= $e($shop['jam_buka']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($shop['telepon'])): ?>
                        <p style="font-size: 13px; color: #6b7280; margin: 0 0 8px;">📞 <?= $e($shop['telepon']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($shop['harga'])): ?>
                        <p style="font-size: 14px; font-weight: 600; color: #d97706; margin: 0 0 12px;">Estimasi Harga: Rp <?= number_format($shop['harga'], 0, ',', '.') ?></p>
                    <?php endif; ?>
                    <?php if (!empty($shop['maps_url'])): ?>
                        <a href="<?= $e($shop['maps_url']) ?>" target="_blank" rel="noopener noreferrer" style="display: inline-block; font-size: 13px; font-weight: 500; padding: 6px 12px; text-decoration: none; border-radius: 6px; background: #059669; color: #ffffff;">Navigasi Peta <?= $ui->icon('external') ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</article>
