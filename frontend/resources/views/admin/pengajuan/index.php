<div class="admin-heading">
    <div>
        <h1><?= $e($title) ?></h1>
        <p>Tinjau dan moderasi pengajuan promosi tempat makan dari pemilik usaha kuliner daerah Riau.</p>
    </div>
</div>

<?php if ($d['status']): ?>
    <div class="notice notice-success" role="status" style="margin-bottom: 20px; padding: 14px 18px; border-radius: 10px; background: #e6f4ea; border: 1px solid #34a853; color: #137333;">
        <?= $ui->icon('check') ?> <strong><?= $e($d['status']) ?></strong>
    </div>
<?php endif; ?>

<!-- Status Filters -->
<div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
    <a href="<?= $e($ui->url('admin.promotions.index', [], ['status' => 'all'])) ?>" style="padding:8px 16px; border-radius:20px; text-decoration:none; font-size:13px; font-weight:600; <?= $d['current_status'] === 'all' ? 'background:var(--green, #0f766e); color:#fff;' : 'background:#e5e7eb; color:#374151;' ?>">
        Semua (<?= $d['counts']['all'] ?>)
    </a>
    <a href="<?= $e($ui->url('admin.promotions.index', [], ['status' => 'pending'])) ?>" style="padding:8px 16px; border-radius:20px; text-decoration:none; font-size:13px; font-weight:600; <?= $d['current_status'] === 'pending' ? 'background:#d97706; color:#fff;' : 'background:#fef3c7; color:#92400e;' ?>">
        Menunggu Verifikasi (<?= $d['counts']['pending'] ?>)
    </a>
    <a href="<?= $e($ui->url('admin.promotions.index', [], ['status' => 'approved'])) ?>" style="padding:8px 16px; border-radius:20px; text-decoration:none; font-size:13px; font-weight:600; <?= $d['current_status'] === 'approved' ? 'background:#16a34a; color:#fff;' : 'background:#dcfce7; color:#166534;' ?>">
        Disetujui (<?= $d['counts']['approved'] ?>)
    </a>
    <a href="<?= $e($ui->url('admin.promotions.index', [], ['status' => 'rejected'])) ?>" style="padding:8px 16px; border-radius:20px; text-decoration:none; font-size:13px; font-weight:600; <?= $d['current_status'] === 'rejected' ? 'background:#dc2626; color:#fff;' : 'background:#fee2e2; color:#991b1b;' ?>">
        Ditolak (<?= $d['counts']['rejected'] ?>)
    </a>
</div>

<?php if (!empty($d['submissions'])): ?>
    <div class="table-panel">
        <div class="table-scroller" role="region" aria-label="Tabel Pengajuan Toko" tabindex="0">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Nama Toko & Pemilik</th>
                        <th scope="col">Wilayah</th>
                        <th scope="col">Menu Unggulan</th>
                        <th scope="col">Alamat & Kontak</th>
                        <th scope="col">Peta / Rute</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:center;">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($d['submissions'] as $sub): ?>
                        <tr>
                            <td>
                                <strong style="display:block; color:var(--text-dark, #1f2937);"><?= $e($sub['nama_toko']) ?></strong>
                                <small style="color:#6b7280;">Pemilik: <?= $e($sub['nama_pemilik'] ?: '—') ?></small>
                                <br><small style="color:#9ca3af; font-size:11px;"><?= $e($sub['created_at']) ?></small>
                            </td>
                            <td><?= $e($sub['region_name']) ?></td>
                            <td><span class="category-tag"><?= $e($sub['nama_makanan'] ?: 'Kuliner Daerah') ?></span></td>
                            <td>
                                <div style="max-width:240px; font-size:13px;">
                                    <?= $e($sub['alamat']) ?>
                                    <?php if (!empty($sub['telepon'])): ?>
                                        <br><strong style="color:var(--green, #0f766e);">WA: <?= $e($sub['telepon']) ?></strong>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($sub['maps_url'])): ?>
                                    <a href="<?= $e($sub['maps_url']) ?>" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:4px; font-size:12px; color:#2563eb; text-decoration:underline;">
                                        <?= $ui->icon('map') ?> Buka Link
                                    </a>
                                <?php elseif (!empty($sub['latitude'])): ?>
                                    <span style="font-size:11px; color:#6b7280;"><?= $sub['latitude'] ?>, <?= $sub['longitude'] ?></span>
                                <?php else: ?>
                                    <span style="color:#9ca3af; font-size:12px;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($sub['status'] === 'pending'): ?>
                                    <span style="display:inline-block; padding:3px 10px; border-radius:12px; background:#fef3c7; color:#92400e; font-weight:700; font-size:12px;">Menunggu</span>
                                <?php elseif ($sub['status'] === 'approved'): ?>
                                    <span style="display:inline-block; padding:3px 10px; border-radius:12px; background:#dcfce7; color:#166534; font-weight:700; font-size:12px;">Disetujui</span>
                                <?php else: ?>
                                    <span style="display:inline-block; padding:3px 10px; border-radius:12px; background:#fee2e2; color:#991b1b; font-weight:700; font-size:12px;">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <?php if ($sub['status'] === 'pending'): ?>
                                    <div style="display:flex; gap:6px; justify-content:center;">
                                        <form method="post" action="<?= $e($ui->url('admin.promotions.approve', ['id' => $sub['id']])) ?>">
                                            <?= $ui->csrf() ?>
                                            <button type="submit" class="button button-small" style="background:#16a34a; color:#fff; border:none; padding:6px 12px; font-size:12px; font-weight:600; border-radius:6px; cursor:pointer;" onclick="return confirm('Setujui dan otomatis terbitkan toko ini ke Direktori Publik?')">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="post" action="<?= $e($ui->url('admin.promotions.reject', ['id' => $sub['id']])) ?>">
                                            <?= $ui->csrf() ?>
                                            <button type="submit" class="button button-small" style="background:#dc2626; color:#fff; border:none; padding:6px 12px; font-size:12px; font-weight:600; border-radius:6px; cursor:pointer;" onclick="return confirm('Tolak pengajuan toko ini?')">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <small style="color:#6b7280; font-style:italic; font-size:11px;">Selesai dimoderasi</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="table-note"><?= count($d['submissions']) ?> pengajuan ditampilkan.</p>
    </div>
<?php else: ?>
    <div class="empty-state">
        <span class="empty-icon"><?= $ui->icon('check') ?></span>
        <h3>Tidak ada pengajuan promosi.</h3>
        <p>Belum ada pengajuan toko pada kategori status ini.</p>
    </div>
<?php endif; ?>
