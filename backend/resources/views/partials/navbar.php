<header class="site-header">
    <div class="container nav-shell">
        <?php $ui->partial('partials.brand'); ?>
        <button class="icon-button mobile-toggle" type="button" data-menu-toggle="public-menu" aria-controls="public-menu" aria-expanded="false" aria-label="Buka menu navigasi"><?= $ui->icon('menu') ?></button>
        <nav class="public-nav" id="public-menu" aria-label="Navigasi utama">
            <div class="nav-links">
                <?php foreach (['home' => 'Beranda', 'catalog' => 'Jelajah Kuliner', 'shops.browse' => 'Direktori Toko', 'about' => 'Tentang'] as $route => $label): ?>
                    <a href="<?= $e($ui->url($route)) ?>" <?= $active === $route ? 'aria-current="page"' : '' ?>><?= $e($label) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="nav-account">
                <?php if ($d['user']): ?>
                    <span class="account-name"><?= $ui->icon('user') ?><?= $e($d['user']['name']) ?></span>
                    <?php if (!empty($d['user']['is_admin'])): ?><a class="button button-ghost button-small" href="<?= $e($ui->url('admin.home')) ?>">Admin <?= $ui->icon('arrow') ?></a><?php endif; ?>
                    <form action="<?= $e($ui->url('logout')) ?>" method="post" <?= !$ui->options['connected'] ? 'data-preview-form' : '' ?>>
                        <?= $ui->csrf() ?><button type="submit" class="button button-outline button-small">Keluar <?= $ui->icon('logout') ?></button>
                        <p class="form-feedback" role="status"></p>
                    </form>
                <?php else: ?>
                    <a class="button button-ghost button-small" href="<?= $e($ui->url('register')) ?>">Daftar</a>
                    <a class="button button-primary button-small" href="<?= $e($ui->url('login')) ?>">Masuk <?= $ui->icon('arrow') ?></a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
