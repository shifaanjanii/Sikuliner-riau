<!doctype html>
<html lang="id">
<head><?php $ui->partial('partials.head', ['title' => $title]); ?></head>
<body class="admin-body">
    <a class="skip-link" href="#konten">Langsung ke konten</a>
    <div class="sidebar-backdrop" data-sidebar-backdrop hidden></div>
    <?php $ui->partial('partials.sidebar', ['active' => $active ?? '']); ?>
    <div class="admin-workspace">
        <header class="admin-topbar">
            <div class="admin-title"><button type="button" class="icon-button admin-menu-toggle" data-menu-toggle="admin-sidebar" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka menu admin"><?= $ui->icon('menu') ?></button><span><?= $e($title) ?></span></div>
            <span class="admin-identity"><span class="avatar"><?= $ui->icon('user') ?></span><?= $e($d['user']['name'] ?? 'Pratinjau antarmuka') ?></span>
        </header>
        <main id="konten" class="admin-content">
            <?php if ($ui->options['adminPreview']): ?><div class="notice notice-preview"><?= $ui->icon('info') ?><span><strong>Pratinjau front-end admin.</strong> Tidak ada akun yang masuk. Data dan perubahan pada halaman ini tidak disimpan.</span></div><?php endif; ?>
            <?php if ($d['status']): ?><div class="notice notice-success" role="status"><?= $ui->icon('check') ?><span><?= $e($d['status']) ?></span></div><?php endif; ?>
            <?php if ($ui->error('general')): ?><div class="notice notice-error" role="alert"><?= $ui->icon('info') ?><span><?= $e($ui->error('general')) ?></span></div><?php endif; ?>
            <?= $content ?>
        </main>
        <footer class="admin-footer">SiKuliner Riau <span>Ruang pengelola kuliner</span></footer>
    </div>
    <dialog class="confirm-dialog" id="delete-dialog" aria-labelledby="delete-title" aria-describedby="delete-description">
        <div class="dialog-content"><span class="dialog-icon"><?= $ui->icon('trash') ?></span><h2 id="delete-title">Hapus data ini?</h2><p id="delete-description">Data <strong data-delete-name></strong> akan dihapus. Pastikan data sudah tidak diperlukan.</p><p class="text-muted">Wilayah yang masih digunakan oleh makanan mengikuti aturan penghapusan dari server.</p><div class="dialog-actions"><button type="button" class="button button-outline" data-dialog-cancel>Batal</button><button type="button" class="button button-danger" data-dialog-confirm>Ya, hapus</button></div></div>
    </dialog>
</body>
</html>
