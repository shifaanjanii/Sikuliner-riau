<!doctype html>
<html lang="id">
<head>
    <?php $ui->partial('partials.head', ['title' => $title ?? 'SiKuliner Riau', 'publicPage' => true]); ?>
</head>
<body>
    <a class="skip-link" href="#konten">Langsung ke konten</a>
    <?php $ui->partial('partials.navbar', ['active' => $active ?? '']); ?>
    <?php $ui->partial('partials.notices'); ?>
    <main id="konten"><?= $content ?></main>
    <?php $ui->partial('partials.footer'); ?>
</body>
</html>
