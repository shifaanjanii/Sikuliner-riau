<footer class="site-footer">
    <div class="container footer-top">
        <div><?php $ui->partial('partials.brand'); ?><p>Kenali daerahnya.<br>Temukan cerita di setiap rasa.</p></div>
        <div class="footer-links"><span>Mulai mengenal</span><a href="<?= $e($ui->url('home')) ?>">Beranda</a><a href="<?= $e($ui->url('catalog')) ?>">Jelajah Kuliner</a><a href="<?= $e($ui->url('about')) ?>">Tentang SiKuliner</a></div>
        <div class="footer-note"><?= $ui->icon('leaf') ?><p>Dari Riau, untuk kamu<br>yang selalu ingin tahu.</p></div>
    </div>
    <div class="container footer-bottom"><span>© <?= date('Y') ?> SiKuliner Riau</span><span>Sebuah perjalanan mengenal rasa lokal.</span></div>
</footer>
