<?php if ($d['pages'] > 1): ?>
<nav class="pagination" aria-label="Halaman hasil katalog">
    <?php if ($d['page'] > 1): ?><a href="<?= $e($ui->url($route, [], ['q' => $d['q'], 'region' => $d['region'], 'page' => $d['page'] - 1])) ?>#jelajah" aria-label="Halaman sebelumnya"><?= $ui->icon('arrow-left') ?></a><?php endif; ?>
    <?php for ($number = max(1, $d['page'] - 2); $number <= min($d['pages'], $d['page'] + 2); $number++): ?>
        <a href="<?= $e($ui->url($route, [], ['q' => $d['q'], 'region' => $d['region'], 'page' => $number])) ?>#jelajah" <?= $number === (int) $d['page'] ? 'aria-current="page"' : '' ?> aria-label="Halaman <?= $number ?>"><?= $number ?></a>
    <?php endfor; ?>
    <?php if ($d['page'] < $d['pages']): ?><a href="<?= $e($ui->url($route, [], ['q' => $d['q'], 'region' => $d['region'], 'page' => $d['page'] + 1])) ?>#jelajah" aria-label="Halaman berikutnya"><?= $ui->icon('arrow') ?></a><?php endif; ?>
</nav>
<?php endif; ?>
