<aside class="admin-sidebar" id="admin-sidebar" aria-label="Navigasi admin">
    <div class="sidebar-brand"><?php $ui->partial('partials.brand'); ?><button class="icon-button sidebar-close" type="button" data-menu-close="admin-sidebar" aria-label="Tutup menu admin"><?= $ui->icon('close') ?></button></div>
    <span class="sidebar-caption">RUANG PENGELOLA</span>
    <nav class="sidebar-nav">
        <?php foreach (['admin.home' => ['home', 'Beranda Admin'], 'regions.index' => ['pin', 'Kabupaten/Kota'], 'foods.index' => ['food', 'Makanan Khas']] as $route => [$icon, $label]): ?>
        <a href="<?= $e($ui->url($route)) ?>" <?= $active === $route ? 'aria-current="page"' : '' ?>><?= $ui->icon($icon) ?><span><?= $e($label) ?></span><?= $ui->icon('chevron', 'sidebar-chevron') ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-bottom">
        <a href="<?= $e($ui->url('home')) ?>"><?= $ui->icon('external') ?> Lihat Website</a>
        <?php if ($d['user']): ?><form action="<?= $e($ui->url('logout')) ?>" method="post" <?= !$ui->options['connected'] ? 'data-preview-form' : '' ?>><?= $ui->csrf() ?><button class="sidebar-logout" type="submit"><?= $ui->icon('logout') ?> Keluar</button><p class="form-feedback" role="status"></p></form>
        <?php else: ?><button class="sidebar-logout" type="button" disabled title="Tidak ada sesi akun pada pratinjau"><?= $ui->icon('logout') ?> Keluar <span class="sr-only">Tidak tersedia pada pratinjau</span></button><?php endif; ?>
        <div class="sidebar-footnote"><?= $ui->icon('leaf') ?><span>Merawat cerita.<br>Mengenalkan rasa.</span></div>
    </div>
</aside>
