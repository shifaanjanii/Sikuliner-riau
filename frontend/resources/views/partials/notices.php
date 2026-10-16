<?php if ($ui->options['demo']): ?>
    <aside class="demo-banner" aria-label="Mode pengembangan">Data contoh untuk pratinjau tampilan. Bukan katalog resmi. <a href="<?= $e($ui->options['base'] . '/') ?>">Keluar dari mode contoh <?= $ui->icon('arrow') ?></a></aside>
<?php endif; ?>
<?php if ($d['status']): ?><div class="container"><div class="notice notice-success" role="status"><?= $ui->icon('check') ?><span><?= $e($d['status']) ?></span></div></div><?php endif; ?>
<?php if ($ui->error('general')): ?><div class="container"><div class="notice notice-error" role="alert"><?= $ui->icon('info') ?><span><?= $e($ui->error('general')) ?></span></div></div><?php endif; ?>
