<div class="admin-heading">
    <div>
        <h1><?= $e($title) ?></h1>
        <p>Pantau dan kelola seluruh ulasan serta rating yang dikirimkan oleh wisatawan dan pengunjung.</p>
    </div>
</div>

<?php if ($d['status']): ?>
    <div class="notice notice-success" role="status" style="margin-bottom: 20px; padding: 14px 18px; border-radius: 10px; background: #e6f4ea; border: 1px solid #34a853; color: #137333;">
        <?= $ui->icon('check') ?> <strong><?= $e($d['status']) ?></strong>
    </div>
<?php endif; ?>

<!-- Summary Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
    <div style="background:var(--bg-card, #fff); padding:18px 22px; border-radius:12px; border:1px solid var(--border-color, #e5e7eb);">
        <span style="font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Total Ulasan Masuk</span>
        <div style="font-size:28px; font-weight:800; color:var(--text-dark, #1f2937); margin-top:4px;"><?= $d['total_reviews'] ?></div>
    </div>
    <div style="background:var(--bg-card, #fff); padding:18px 22px; border-radius:12px; border:1px solid var(--border-color, #e5e7eb);">
        <span style="font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Rata-Rata Rating Kepuasan</span>
        <div style="font-size:28px; font-weight:800; color:#d97706; margin-top:4px; display:flex; align-items:center; gap:6px;">
            <span>★</span> <?= $d['avg_overall_rating'] ?: '0.0' ?> <span style="font-size:14px; color:#6b7280; font-weight:400;">/ 5.0</span>
        </div>
    </div>
</div>

<?php if (!empty($d['reviews'])): ?>
    <div class="table-panel">
        <div class="table-scroller" role="region" aria-label="Tabel Moderasi Ulasan" tabindex="0">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Toko yang Diulas</th>
                        <th scope="col">Pengulas</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Komentar</th>
                        <th scope="col">Waktu</th>
                        <th scope="col" style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($d['reviews'] as $rev): ?>
                        <tr>
                            <td>
                                <strong style="color:var(--text-dark, #1f2937);"><?= $e($rev['toko_name']) ?></strong>
                            </td>
                            <td>
                                <strong><?= $e($rev['nama_pengulas']) ?></strong>
                                <?php if (!empty($rev['user_id'])): ?>
                                    <br><small style="color:var(--green, #0f766e);">Terverifikasi</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="color:#d97706; font-size:15px; font-weight:700;">
                                    <?= str_repeat('★', (int) $rev['rating']) . str_repeat('☆', 5 - (int) $rev['rating']) ?>
                                    <span style="font-size:12px; color:#92400e; margin-left:4px;">(<?= $rev['rating'] ?>)</span>
                                </div>
                            </td>
                            <td>
                                <p class="table-description" style="max-width:320px;"><?= $e($rev['komentar']) ?></p>
                            </td>
                            <td>
                                <small style="color:#6b7280;"><?= $e($rev['formatted_date']) ?></small>
                            </td>
                            <td style="text-align:center;">
                                <form method="post" action="<?= $e($ui->url('admin.reviews.destroy', ['id' => $rev['id']])) ?>" onsubmit="return confirm('Hapus ulasan ini dari sistem?')">
                                    <?= $ui->csrf() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="icon-button delete-button" aria-label="Hapus ulasan" title="Hapus ulasan">
                                        <?= $ui->icon('trash') ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="table-note"><?= count($d['reviews']) ?> ulasan ditampilkan.</p>
    </div>
<?php else: ?>
    <div class="empty-state">
        <span class="empty-icon"><?= $ui->icon('star') ?></span>
        <h3>Belum ada ulasan masuk.</h3>
        <p>Ulasan dan rating dari pengunjung akan otomatis muncul di halaman ini.</p>
    </div>
<?php endif; ?>

