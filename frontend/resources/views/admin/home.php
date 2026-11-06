<section class="admin-welcome" style="margin-bottom: 28px;">
    <span class="eyebrow">PANEL ADMINISTRATOR</span>
    <h1>Dashboard Ringkasan SiKuliner Riau</h1>
    <p>Kelola data SiKuliner Riau dan pantau perkembangan data kuliner khas, direktori toko, pengajuan promosi masuk, serta ulasan wisatawan dari 12 Kabupaten/Kota se-Provinsi Riau.</p>
</section>

<!-- Sprint 5: Kartu Ringkasan Statistik Sistem -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 32px;">
    <!-- Card 1: Wilayah -->
    <div style="background: var(--bg-card, #fff); padding: 20px; border-radius: 14px; border: 1px solid var(--border-color, #e5e7eb); box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Kabupaten/Kota</span>
            <div style="width:36px; height:36px; border-radius:8px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center;">
                <?= $ui->icon('pin') ?>
            </div>
        </div>
        <div style="font-size: 30px; font-weight: 800; color: #1e293b; margin-top: 8px;"><?= $d['stats']['total_regions'] ?? 12 ?></div>
        <span style="font-size: 12px; color: #64748b;">Wilayah se-Provinsi Riau</span>
    </div>

    <!-- Card 2: Kuliner Khas -->
    <div style="background: var(--bg-card, #fff); padding: 20px; border-radius: 14px; border: 1px solid var(--border-color, #e5e7eb); box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Makanan Khas</span>
            <div style="width:36px; height:36px; border-radius:8px; background:#f0fdf4; color:#16a34a; display:flex; align-items:center; justify-content:center;">
                <?= $ui->icon('food') ?>
            </div>
        </div>
        <div style="font-size: 30px; font-weight: 800; color: #1e293b; margin-top: 8px;"><?= $d['stats']['total_foods'] ?? 0 ?></div>
        <span style="font-size: 12px; color: #64748b;">Katalog kuliner daerah</span>
    </div>

    <!-- Card 3: Toko Terdaftar -->
    <div style="background: var(--bg-card, #fff); padding: 20px; border-radius: 14px; border: 1px solid var(--border-color, #e5e7eb); box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Direktori Toko</span>
            <div style="width:36px; height:36px; border-radius:8px; background:#faf5ff; color:#9333ea; display:flex; align-items:center; justify-content:center;">
                <?= $ui->icon('pin') ?>
            </div>
        </div>
        <div style="font-size: 30px; font-weight: 800; color: #1e293b; margin-top: 8px;"><?= $d['stats']['total_shops'] ?? 0 ?></div>
        <span style="font-size: 12px; color: #64748b;">Rumah makan & gerai oleh-oleh</span>
    </div>

    <!-- Card 4: Pengajuan Promosi -->
    <div style="background: var(--bg-card, #fff); padding: 20px; border-radius: 14px; border: 1px solid var(--border-color, #e5e7eb); box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Pengajuan Promosi</span>
            <div style="width:36px; height:36px; border-radius:8px; background:#fffbeb; color:#d97706; display:flex; align-items:center; justify-content:center;">
                <?= $ui->icon('check') ?>
            </div>
        </div>
        <div style="font-size: 30px; font-weight: 800; color: #1e293b; margin-top: 8px; display:flex; align-items:baseline; gap:8px;">
            <?= $d['stats']['total_promotions'] ?? 0 ?>
            <?php if (($d['stats']['pending_promotions'] ?? 0) > 0): ?>
                <span style="font-size: 12px; font-weight:700; background:#fef3c7; color:#92400e; padding:2px 8px; border-radius:10px;">
                    <?= $d['stats']['pending_promotions'] ?> Pending
                </span>
            <?php endif; ?>
        </div>
        <span style="font-size: 12px; color: #64748b;">Pengajuan kemitraan publik</span>
    </div>

    <!-- Card 5: Ulasan Wisatawan -->
    <div style="background: var(--bg-card, #fff); padding: 20px; border-radius: 14px; border: 1px solid var(--border-color, #e5e7eb); box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Ulasan Wisatawan</span>
            <div style="width:36px; height:36px; border-radius:8px; background:#fff1f2; color:#e11d48; display:flex; align-items:center; justify-content:center;">
                <?= $ui->icon('star') ?>
            </div>
        </div>
        <div style="font-size: 30px; font-weight: 800; color: #1e293b; margin-top: 8px; display:flex; align-items:baseline; gap:6px;">
            <?= $d['stats']['total_reviews'] ?? 0 ?>
            <span style="font-size: 14px; font-weight:700; color:#d97706;">★ <?= $d['stats']['avg_rating'] ?? '0.0' ?></span>
        </div>
        <span style="font-size: 12px; color: #64748b;">Rating kepuasan pengguna</span>
    </div>
</div>

<!-- Sprint 5: Grafik Sebaran Toko per Wilayah (Chart.js) -->
<div style="background: var(--bg-card, #fff); padding: 24px 28px; border-radius: 16px; border: 1px solid var(--border-color, #e5e7eb); margin-bottom: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <div>
            <h2 style="font-size: 18px; margin: 0; color: var(--text-dark, #1f2937);">Sebaran Toko Kuliner per Kabupaten/Kota se-Riau</h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #6b7280;">Visualisasi jumlah rumah makan dan toko oleh-oleh terdata pada 12 wilayah.</p>
        </div>
        <span style="font-size:12px; font-weight:600; padding:4px 10px; background:#e0f2fe; color:#0369a1; border-radius:12px;">Grafik Interaktif</span>
    </div>
    <div style="position:relative; height: 300px; width:100%;">
        <canvas id="shopsDistributionChart"></canvas>
    </div>
</div>

<!-- Sprint 4 & 5 Activity Feeds: Pengajuan Toko Terbaru & Ulasan Terbaru -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; margin-bottom: 36px;">
    <!-- Seksi Pengajuan Promosi Toko Terbaru -->
    <div style="background: var(--bg-card, #fff); padding: 24px; border-radius: 16px; border: 1px solid var(--border-color, #e5e7eb);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size: 16px; margin: 0; color: #1f2937;">Pengajuan Toko Terbaru</h3>
            <a href="<?= $e($ui->url('admin.promotions.index')) ?>" style="font-size: 13px; font-weight:600; color:var(--green, #0f766e); text-decoration:none;">Lihat Semua &rarr;</a>
        </div>
        <?php if (!empty($d['recent_promotions'])): ?>
            <div style="display:flex; flex-direction:column; gap:12px;">
                <?php foreach ($d['recent_promotions'] as $promo): ?>
                    <div style="padding:12px 14px; border-radius:10px; background:#f9fafb; border:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong style="font-size:14px; color:#1f2937; display:block;"><?= $e($promo['nama_toko']) ?></strong>
                            <small style="color:#6b7280; font-size:12px;"><?= $e($promo['region_name']) ?> &bull; <?= $e($promo['nama_makanan'] ?: 'Kuliner Daerah') ?></small>
                        </div>
                        <div>
                            <?php if ($promo['status'] === 'pending'): ?>
                                <span style="font-size:11px; font-weight:700; background:#fef3c7; color:#92400e; padding:3px 8px; border-radius:8px;">Menunggu</span>
                            <?php elseif ($promo['status'] === 'approved'): ?>
                                <span style="font-size:11px; font-weight:700; background:#dcfce7; color:#166534; padding:3px 8px; border-radius:8px;">Disetujui</span>
                            <?php else: ?>
                                <span style="font-size:11px; font-weight:700; background:#fee2e2; color:#991b1b; padding:3px 8px; border-radius:8px;">Ditolak</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="font-size:13px; color:#6b7280; margin:0;">Belum ada pengajuan promosi toko baru.</p>
        <?php endif; ?>
    </div>

    <!-- Seksi Ulasan Pengunjung Terbaru -->
    <div style="background: var(--bg-card, #fff); padding: 24px; border-radius: 16px; border: 1px solid var(--border-color, #e5e7eb);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size: 16px; margin: 0; color: #1f2937;">Ulasan Wisatawan Terbaru</h3>
            <a href="<?= $e($ui->url('admin.reviews.index')) ?>" style="font-size: 13px; font-weight:600; color:var(--green, #0f766e); text-decoration:none;">Lihat Semua &rarr;</a>
        </div>
        <?php if (!empty($d['recent_reviews'])): ?>
            <div style="display:flex; flex-direction:column; gap:12px;">
                <?php foreach ($d['recent_reviews'] as $rev): ?>
                    <div style="padding:12px 14px; border-radius:10px; background:#f9fafb; border:1px solid #e5e7eb;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <strong style="font-size:13px; color:#1f2937;"><?= $e($rev['toko_name']) ?></strong>
                            <span style="color:#d97706; font-size:13px; font-weight:700;"><?= str_repeat('★', (int) $rev['rating']) ?></span>
                        </div>
                        <p style="margin:0; font-size:13px; color:#4b5563; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            "<?= $e($rev['komentar']) ?>"
                        </p>
                        <small style="color:#9ca3af; font-size:11px; margin-top:4px; display:block;">Oleh: <?= $e($rev['nama_pengulas']) ?> &bull; <?= $e($rev['formatted_date']) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="font-size:13px; color:#6b7280; margin:0;">Belum ada ulasan wisatawan yang masuk.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Shortcut Navigasi Pengelolaan -->
<h2 class="admin-section-heading">Menu Pengelolaan Sistem</h2>
<div class="shortcut-grid">
    <a class="shortcut-card" href="<?= $e($ui->url('regions.index')) ?>">
        <span class="shortcut-icon"><?= $ui->icon('pin') ?></span>
        <h3>Kabupaten/Kota</h3>
        <p>Tambah dan perbarui data 12 wilayah asal kuliner.</p>
        <span class="text-link">Kelola wilayah <?= $ui->icon('arrow') ?></span>
    </a>
    <a class="shortcut-card" href="<?= $e($ui->url('foods.index')) ?>">
        <span class="shortcut-icon"><?= $ui->icon('food') ?></span>
        <h3>Makanan Khas</h3>
        <p>Kelola nama, foto, kategori, dan deskripsi sejarah kuliner.</p>
        <span class="text-link">Kelola makanan <?= $ui->icon('arrow') ?></span>
    </a>
    <a class="shortcut-card" href="<?= $e($ui->url('shops.index')) ?>">
        <span class="shortcut-icon"><?= $ui->icon('pin') ?></span>
        <h3>Direktori Toko</h3>
        <p>Kelola alamat, jam operasional, link peta, dan harga menu.</p>
        <span class="text-link">Kelola toko <?= $ui->icon('arrow') ?></span>
    </a>
    <a class="shortcut-card" href="<?= $e($ui->url('admin.promotions.index')) ?>">
        <span class="shortcut-icon"><?= $ui->icon('check') ?></span>
        <h3>Pengajuan Toko</h3>
        <p>Setujui atau tolak pengajuan kemitraan dari pemilik usaha.</p>
        <span class="text-link">Moderasi pengajuan <?= $ui->icon('arrow') ?></span>
    </a>
    <a class="shortcut-card" href="<?= $e($ui->url('admin.reviews.index')) ?>">
        <span class="shortcut-icon"><?= $ui->icon('star') ?></span>
        <h3>Moderasi Ulasan</h3>
        <p>Pantau testimoni, rating kepuasan bintang, dan hapus ulasan.</p>
        <span class="text-link">Moderasi ulasan <?= $ui->icon('arrow') ?></span>
    </a>
</div>

<!-- Chart.js CDN & Inisialisasi Grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('shopsDistributionChart');
    if (!ctx) return;

    const labels = <?= json_encode($d['chart_labels'] ?? []) ?>;
    const dataValues = <?= json_encode($d['chart_values'] ?? []) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Toko Terdaftar',
                data: dataValues,
                backgroundColor: 'rgba(15, 118, 110, 0.75)',
                borderColor: 'rgba(15, 118, 110, 1)',
                borderWidth: 1.5,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.y + ' Toko / Tempat Makan';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
