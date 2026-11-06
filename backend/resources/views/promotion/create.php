<section class="page-heading">
    <div class="container">
        <span class="eyebrow">FORMULIR PROMOSI MITRA KULINER</span>
        <h1>Promosikan Toko Anda di <span style="color:var(--green)">SiKuliner Riau</span></h1>
        <p>Apakah Anda memiliki rumah makan, kedai kopi, atau gerai oleh-oleh kuliner khas Riau? Daftarkan usaha Anda agar mudah ditemukan oleh wisatawan.</p>
    </div>
</section>

<div class="container" style="max-width: 860px; margin: 0 auto 60px auto;">
    <?php if (!$d['user']): ?>
        <!-- Callout Banner untuk Pengunjung yang Belum Login -->
        <div style="margin-bottom: 24px; padding: 18px 22px; border-radius: 14px; background: #f0fdf4; border: 1px solid #bbf7d0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="font-size: 26px;">💡</span>
                <div>
                    <strong style="display:block; font-size:15px; color:#166534;">Ingin Mempromosikan Tempat Makan Anda?</strong>
                    <span style="font-size:13px; color:#15803d;">Disarankan untuk <strong>Masuk (Login)</strong> atau <strong>Daftar Akun</strong> terlebih dahulu agar pengajuan toko terhubung dengan akun Anda.</span>
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="<?= $e($ui->url('login')) ?>" class="button button-outline button-small" style="background:#fff; border-color:#16a34a; color:#16a34a; font-weight:600;">
                    Masuk <?= $ui->icon('arrow') ?>
                </a>
                <a href="<?= $e($ui->url('register')) ?>" class="button button-primary button-small" style="font-weight:600;">
                    Daftar Akun
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Banner Akun Terverifikasi -->
        <div style="margin-bottom: 24px; padding: 14px 20px; border-radius: 12px; background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1; display:flex; align-items:center; gap:10px;">
            <?= $ui->icon('check') ?>
            <span style="font-size:14px;">Anda mengajukan promosi sebagai mitra terverifikasi: <strong><?= $e($d['user']['name']) ?></strong></span>
        </div>
    <?php endif; ?>

    <?php if ($d['status']): ?>
        <div class="notice notice-success" role="status" style="margin-bottom: 24px; padding: 16px 20px; border-radius: 12px; background: #e6f4ea; border: 1px solid #34a853; color: #137333;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:20px;">✅</span>
                <strong><?= $e($d['status']) ?></strong>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($d['errors'])): ?>
        <div class="notice notice-error" role="alert" style="margin-bottom: 24px; padding: 16px 20px; border-radius: 12px; background: #fce8e6; border: 1px solid #ea4335; color: #c5221f;">
            <strong>Mohon periksa isian formulir:</strong>
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                <?php foreach ($d['errors'] as $field => $errs): ?>
                    <?php foreach ((array) $errs as $err): ?>
                        <li><?= $e($err) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="background: var(--bg-card, #ffffff); padding: 32px; border-radius: 16px; border: 1px solid var(--border-color, #e5e7eb); box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
        <div style="border-bottom: 1px solid var(--border-color, #f0f0f0); padding-bottom: 16px; margin-bottom: 24px;">
            <h2 style="font-size: 20px; margin: 0 0 6px 0; color: var(--text-dark, #1f2937);">Informasi Tempat Makan</h2>
            <p style="margin: 0; font-size: 14px; color: var(--text-muted, #6b7280);">Isi data lengkap tempat usaha Anda. Pengajuan akan dimoderasi oleh tim admin sebelum terbit.</p>
        </div>

        <form method="post" action="<?= $e($ui->url('promotion.store')) ?>" enctype="multipart/form-data" class="form-stack">
            <?= $ui->csrf() ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 18px;">
                <div>
                    <label for="nama_toko" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Nama Toko / Rumah Makan <span style="color:#e53e3e">*</span></label>
                    <input type="text" id="nama_toko" name="nama_toko" value="<?= $ui->old('nama_toko') ?>" placeholder="Contoh: RM Pondok Patin H. M. Yunus" required style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                </div>

                <div>
                    <label for="nama_pemilik" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Nama Pemilik / Pengelola</label>
                    <input type="text" id="nama_pemilik" name="nama_pemilik" value="<?= $ui->old('nama_pemilik', $d['user']['name'] ?? '') ?>" placeholder="Nama lengkap pemilik usaha" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 18px;">
                <div>
                    <label for="kabupaten_id" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Asal Kabupaten / Kota <span style="color:#e53e3e">*</span></label>
                    <select id="kabupaten_id" name="kabupaten_id" required style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; background:#fff;">
                        <option value="">-- Pilih Wilayah di Riau --</option>
                        <?php foreach ($d['regions'] as $reg): ?>
                            <option value="<?= $e($reg['id']) ?>" <?= (string) $ui->old('kabupaten_id') === (string) $reg['id'] ? 'selected' : '' ?>>
                                <?= $e($reg['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="nama_makanan" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Menu Kuliner Khas Unggulan</label>
                    <input type="text" id="nama_makanan" name="nama_makanan" value="<?= $ui->old('nama_makanan') ?>" placeholder="Contoh: Bolu Kemojo, Gulai Patin, Lopek Bugi" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label for="alamat" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Alamat Jalan Lengkap <span style="color:#e53e3e">*</span></label>
                <textarea id="alamat" name="alamat" rows="2" placeholder="Nama jalan, kelurahan, kecamatan, nomor ruko/patokan" required style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; resize:vertical;"><?= $ui->old('alamat') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 18px;">
                <div>
                    <label for="telepon" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Kontak WhatsApp / Telepon</label>
                    <input type="tel" id="telepon" name="telepon" value="<?= $ui->old('telepon') ?>" placeholder="0812xxxxxxx" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                </div>

                <div>
                    <label for="jam_buka" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Jam Operasional</label>
                    <input type="text" id="jam_buka" name="jam_buka" value="<?= $ui->old('jam_buka') ?>" placeholder="Contoh: 08.00 - 21.00 WIB" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label for="maps_url" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Link Google Maps (Lokasi Toko)</label>
                <input type="url" id="maps_url" name="maps_url" value="<?= $ui->old('maps_url') ?>" placeholder="https://maps.google.com/?q=..." style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                <small style="color:#6b7280; font-size:12px; margin-top:4px; display:block;">Salin link titik lokasi dari aplikasi Google Maps untuk mempermudah navigasi rute pengunjung.</small>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 18px;">
                <div>
                    <label for="latitude" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Titik Koordinat Latitude (Opsional)</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="<?= $ui->old('latitude') ?>" placeholder="Contoh: 0.507067" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                </div>
                <div>
                    <label for="longitude" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Titik Koordinat Longitude (Opsional)</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="<?= $ui->old('longitude') ?>" placeholder="Contoh: 101.447779" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label for="photo" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Foto Tempat Makan / Makanan (Maksimal 2 MB)</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" style="width:100%; padding:8px; border:1px dashed #9ca3af; border-radius:8px; font-size:14px; background:#f9fafb;">
            </div>

            <div style="margin-bottom: 24px;">
                <label for="deskripsi" style="display:block; font-weight:600; margin-bottom:6px; font-size:14px;">Deskripsi Singkat Usaha / Keunggulan</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Ceritakan keistimewaan rasa, sejarah resep, atau fasilitas yang tersedia..." style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; resize:vertical;"><?= $ui->old('deskripsi') ?></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px;">
                <button type="submit" class="button button-primary" style="padding:12px 28px; font-size:15px; font-weight:600;">
                    Kirim Pengajuan Promosi <?= $ui->icon('arrow') ?>
                </button>
            </div>
        </form>
    </div>
</div>
