<form method="get" action="<?= $e($ui->url($route)) ?>#jelajah" class="filter-panel" data-filter-form>
    <?php if ($ui->options['demo']): ?><input type="hidden" name="demo" value="1"><?php endif; ?>
    <div class="filter-field"><label for="region-filter">Kabupaten/Kota</label><div class="input-wrap"><?= $ui->icon('pin') ?><select id="region-filter" name="<?= $e($ui->queryName('region')) ?>"><option value="">Semua Kabupaten/Kota</option><?php foreach ($d['regions'] as $region): ?><option value="<?= $e($region['id']) ?>" <?= (string) $d['region'] === (string) $region['id'] ? 'selected' : '' ?>><?= $e($region['name']) ?></option><?php endforeach; ?></select></div></div>
    <div class="filter-field"><label for="food-search">Nama makanan</label><div class="input-wrap"><?= $ui->icon('search') ?><input type="search" id="food-search" name="<?= $e($ui->queryName('q')) ?>" value="<?= $e($d['q']) ?>" placeholder="Lagi ingin kenalan dengan makanan apa?" maxlength="160"></div></div>
    <button class="button button-primary" type="submit">Cari Kuliner <?= $ui->icon('arrow') ?></button>
</form>
