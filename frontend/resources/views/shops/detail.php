<article class="container shop-detail-page">
    <a class="back-link" href="<?= $e($ui->url('shops.browse', [], ['region' => $d['region']])) ?>#daftar-toko"><?= $ui->icon('arrow-left') ?> Kembali ke Direktori Toko</a>

    <?php if ($d['status']): ?>
        <div class="notice notice-success" role="status" style="margin: 16px 0; padding: 14px 18px; border-radius: 10px; background: #e6f4ea; border: 1px solid #34a853; color: #137333;">
            <?= $ui->icon('check') ?> <strong><?= $e($d['status']) ?></strong>
        </div>
    <?php endif; ?>

    <div class="shop-detail-grid">
        <div>
            <img class="shop-detail-photo <?= !empty($shop['photo']) ? 'is-photo' : '' ?>" src="<?= $e($ui->photo($shop['photo'] ?? null)) ?>" alt="<?= $e($shop['name']) ?>" width="800" height="600" data-image-fallback="<?= $e($ui->asset('images/food-placeholder.svg')) ?>">
        </div>
        <div class="shop-detail-info">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                <span class="category-tag">TOKO KULINER KHAS</span>
                <?php if (!empty($shop['average_rating'])): ?>
                    <div style="display:inline-flex; align-items:center; gap:6px; background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-weight:700; font-size:14px; border:1px solid #fde68a;">
                        <span style="color:#d97706; font-size:16px;">★</span>
                        <span><?= number_format((float) $shop['average_rating'], 1) ?> / 5.0</span>
                        <span style="font-weight:400; color:#b45309; font-size:12px;">(<?= $shop['reviews_count'] ?> ulasan)</span>
                    </div>
                <?php else: ?>
                    <span style="color:#6b7280; font-size:13px; font-style:italic;">Belum ada ulasan</span>
                <?php endif; ?>
            </div>

            <h1><?= $e($shop['name']) ?></h1>
            <p class="shop-location"><?= $ui->icon('pin') ?><?= $e($shop['region_name']) ?></p>
            <p class="shop-description"><?= $e($shop['deskripsi'] ?: 'Informasi singkat toko sedang dilengkapi.') ?></p>
            
            <dl class="shop-facts">
                <div>
                    <dt>Alamat</dt>
                    <dd><?= $e($shop['alamat']) ?></dd>
                </div>
                <?php if (!empty($shop['jam_buka'])): ?>
                    <div>
                        <dt>Jam operasional</dt>
                        <dd><?= $e($shop['jam_buka']) ?></dd>
                    </div>
                <?php endif; ?>
                <?php if (!empty($shop['telepon'])): ?>
                    <div>
                        <dt>Telepon / WA</dt>
                        <dd><?= $e($shop['telepon']) ?></dd>
                    </div>
                <?php endif; ?>
            </dl>

            <!-- Sprint 4: Google Maps Navigation Button -->
            <?php 
                $mapsDestination = $shop['maps_url'];
                if (empty($mapsDestination) && !empty($shop['latitude']) && !empty($shop['longitude'])) {
                    $mapsDestination = 'https://www.google.com/maps/search/?api=1&query=' . $shop['latitude'] . ',' . $shop['longitude'];
                } elseif (empty($mapsDestination)) {
                    $mapsDestination = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($shop['name'] . ' ' . $shop['alamat'] . ' ' . $shop['region_name']);
                }
            ?>
            <div style="margin-top: 20px;">
                <a href="<?= $e($mapsDestination) ?>" target="_blank" rel="noopener noreferrer" class="button button-primary" style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; font-size:14px; text-decoration:none;">
                    <?= $ui->icon('map') ?> Buka Rute di Google Maps <?= $ui->icon('external') ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Sprint 4: Modul Peta Digital (Maps Integration) -->
    <section class="map-section" style="margin-top: 40px; background: var(--bg-card, #ffffff); padding: 24px; border-radius: 16px; border: 1px solid var(--border-color, #e5e7eb);">
        <div class="section-heading" style="margin-bottom: 16px;">
            <div>
                <span class="eyebrow">INTEGRASI PETA LOKASI</span>
                <h2>Peta dan Rute Menuju Lokasi</h2>
            </div>
        </div>

        <div style="position: relative; width: 100%; height: 340px; border-radius: 12px; overflow: hidden; border: 1px solid #d1d5db;">
            <?php 
                $lat = $shop['latitude'] ?? 0.507067;
                $lng = $shop['longitude'] ?? 101.447779;
                $mapQuery = !empty($shop['latitude']) && !empty($shop['longitude']) 
                    ? ($shop['latitude'] . ',' . $shop['longitude'])
                    : urlencode($shop['name'] . ', ' . $shop['alamat']);
            ?>
            <iframe 
                title="Peta Lokasi <?= $e($shop['name']) ?>"
                width="100%" 
                height="100%" 
                style="border:0;" 
                loading="lazy" 
                allowfullscreen 
                referrerpolicy="no-referrer-when-downgrade"
                src="https://maps.google.com/maps?q=<?= $e($mapQuery) ?>&t=&z=15&ie=UTF8&iwloc=&output=embed">
            </iframe>
        </div>
        <div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
            <span style="font-size:13px; color:#6b7280; display:inline-flex; align-items:center; gap:6px;">
                <?= $ui->icon('pin') ?> Titik Koordinat: <?= $shop['latitude'] ? $shop['latitude'] . ', ' . $shop['longitude'] : 'Sesuai pencarian alamat jalan' ?>
            </span>
            <a href="<?= $e($mapsDestination) ?>" target="_blank" rel="noopener noreferrer" style="font-size:13px; font-weight:600; color:var(--green, #0f766e); text-decoration:underline;">
                Petunjuk Arah Lengkap di Google Maps &rarr;
            </a>
        </div>
    </section>

    <!-- Katalog Menu & Harga -->
    <section class="menu-section" style="margin-top: 40px;">
        <div class="section-heading">
            <div>
                <span class="eyebrow">KATALOG MENU</span>
                <h2>Menu dan harga di <?= $e($shop['name']) ?></h2>
            </div>
        </div>
        <?php if (!empty($shop['menus'])): ?>
            <div class="menu-price-list">
                <?php foreach ($shop['menus'] as $menu): ?>
                    <div class="menu-price-row">
                        <div>
                            <span class="category-tag"><?= $e($menu['category']) ?></span>
                            <h3><?= $e($menu['name']) ?></h3>
                        </div>
                        <strong><?= $menu['price'] !== null ? 'Rp ' . number_format((float) $menu['price'], 0, ',', '.') : 'Harga belum dicantumkan' ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="shop-selection-state">
                <span class="empty-icon"><?= $ui->icon('food') ?></span>
                <h3>Menu sedang dilengkapi.</h3>
                <p>Informasi harga akan ditambahkan oleh pengelola toko.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Sprint 5: Fitur Ulasan (Rating Bintang 1-5 & Komentar) -->
    <section id="ulasan" class="reviews-section" style="margin-top: 48px; padding-top: 32px; border-top: 1px solid var(--border-color, #e5e7eb);">
        <div class="section-heading" style="margin-bottom: 24px;">
            <div>
                <span class="eyebrow">PENGALAMAN KULINER</span>
                <h2>Ulasan dan Rating Pengunjung</h2>
            </div>
            <?php if (!empty($shop['average_rating'])): ?>
                <div style="text-align: right;">
                    <div style="font-size: 28px; font-weight: 800; color: #d97706; display:flex; align-items:center; gap:6px;">
                        <span>★</span> <?= number_format((float) $shop['average_rating'], 1) ?>
                        <span style="font-size:16px; color:#6b7280; font-weight:400;">/ 5.0</span>
                    </div>
                    <span style="font-size: 13px; color: #6b7280;">Berdasarkan <?= $shop['reviews_count'] ?> ulasan</span>
                </div>
            <?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px; align-items: flex-start;">
            <!-- Form Kirim Ulasan -->
            <div style="background: var(--bg-card, #ffffff); padding: 24px; border-radius: 16px; border: 1px solid var(--border-color, #e5e7eb); box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <h3 style="margin: 0 0 8px 0; font-size: 18px;">Beri Ulasan Tempat Ini</h3>
                <p style="margin: 0 0 16px 0; font-size: 13px; color: #6b7280;">Bagikan pengalaman rasa, suasana tempat makan, atau rekomendasi menu Anda.</p>

                <form method="post" action="<?= $e($ui->url('shops.review.store', ['id' => $shop['id']])) ?>">
                    <?= $ui->csrf() ?>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Rating Bintang</label>
                        <div style="display:flex; gap:8px;">
                            <?php for ($star = 5; $star >= 1; $star--): ?>
                                <label style="cursor:pointer; display:flex; align-items:center; gap:4px; padding:6px 12px; border:1px solid #d1d5db; border-radius:20px; font-size:13px; font-weight:600;">
                                    <input type="radio" name="rating" value="<?= $star ?>" <?= $star === 5 ? 'checked' : '' ?> style="accent-color:#d97706;">
                                    <span style="color:#d97706;">★</span> <?= $star ?>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <?php if (!$d['user']): ?>
                        <div style="margin-bottom: 14px;">
                            <label for="nama_pengulas" style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Nama Anda</label>
                            <input type="text" id="nama_pengulas" name="nama_pengulas" placeholder="Contoh: Budi Santoso" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                        </div>
                    <?php else: ?>
                        <div style="margin-bottom: 14px; font-size:13px; color:#4b5563;">
                            Mengulas sebagai: <strong><?= $e($d['user']['name']) ?></strong>
                        </div>
                    <?php endif; ?>

                    <div style="margin-bottom: 18px;">
                        <label for="komentar" style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Komentar Ulasan</label>
                        <textarea id="komentar" name="komentar" rows="3" placeholder="Ceritakan bagaimana kelezatan rasa, kebersihan, porsi, atau keramahan pelayanan..." required style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; resize:vertical;"></textarea>
                    </div>

                    <button type="submit" class="button button-primary" style="width:100%; padding:10px; font-weight:600; font-size:14px;">
                        Kirim Ulasan Anda <?= $ui->icon('arrow') ?>
                    </button>
                </form>
            </div>

            <!-- Daftar Ulasan Masuk -->
            <div>
                <h3 style="margin: 0 0 16px 0; font-size: 18px;">Daftar Ulasan Pengunjung (<?= !empty($shop['reviews']) ? count($shop['reviews']) : 0 ?>)</h3>

                <?php if (!empty($shop['reviews'])): ?>
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <?php foreach ($shop['reviews'] as $rev): ?>
                            <div style="background: var(--bg-card, #ffffff); padding: 18px; border-radius: 12px; border: 1px solid var(--border-color, #e5e7eb);">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:34px; height:34px; border-radius:50%; background:var(--green, #0f766e); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px;">
                                            <?= strtoupper(substr($rev['nama_pengulas'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong style="font-size:14px; display:block; color:var(--text-dark, #1f2937);"><?= $e($rev['nama_pengulas']) ?></strong>
                                            <span style="font-size:12px; color:#6b7280;"><?= $e($rev['created_at']) ?></span>
                                        </div>
                                    </div>
                                    <div style="color:#d97706; font-size:16px; letter-spacing:1px;">
                                        <?= str_repeat('★', (int) $rev['rating']) . str_repeat('☆', 5 - (int) $rev['rating']) ?>
                                    </div>
                                </div>
                                <p style="margin:0; font-size:14px; color:#374151; line-height:1.5;">
                                    <?= nl2br($e($rev['komentar'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align:center; padding:36px 20px; background:#f9fafb; border-radius:12px; border:1px dashed #d1d5db;">
                        <span style="font-size:32px;">💬</span>
                        <h4 style="margin:8px 0 4px 0; color:#374151;">Belum Ada Ulasan</h4>
                        <p style="margin:0; font-size:13px; color:#6b7280;">Jadilah orang pertama yang membagikan ulasan kuliner di toko ini!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</article>
