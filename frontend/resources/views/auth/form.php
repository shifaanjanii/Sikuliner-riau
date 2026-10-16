<div class="auth-heading">
    <span class="eyebrow"><?= $register ? 'AWAL CERITA BARU' : 'SENANG BERTEMU LAGI' ?></span>
    <h1><?= $register ? 'Yuk, kenalan dulu.' : 'Selamat datang<br>kembali.' ?></h1>
    <p><?= $register ? 'Buat akun untuk menjadi bagian dari SiKuliner Riau.' : 'Masuk ke akunmu dan lanjutkan perjalanan rasa.' ?></p>
</div>
<?php if (!$ui->options['connected']): ?><div class="notice notice-soft"><?= $ui->icon('info') ?><span><?= $register ? 'Pendaftaran' : 'Layanan masuk' ?> belum tersedia. Kamu tetap bisa <a href="<?= $e($ui->url('catalog')) ?>">menjelajah kuliner</a> tanpa akun.</span></div><?php endif; ?>
<?php if ($ui->error('general')): ?><div class="notice notice-error" role="alert"><?= $e($ui->error('general')) ?></div><?php endif; ?>
<?php if ($d['status']): ?><div class="notice notice-success" role="status"><?= $e($d['status']) ?></div><?php endif; ?>
<form method="post" action="<?= $e($ui->url($register ? 'register.store' : 'login.store')) ?>" class="form-stack" data-validate <?= !$ui->options['connected'] ? 'data-preview-form' : '' ?>>
    <?= $ui->csrf() ?>
    <?php if ($register): ?>
    <?php $ui->partial('components.field', ['name' => 'name', 'label' => 'Nama lengkap', 'type' => 'text', 'autocomplete' => 'name', 'placeholder' => 'Nama yang ingin kamu gunakan', 'required' => true]); ?>
    <?php endif; ?>
    <?php $ui->partial('components.field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email', 'placeholder' => 'nama@email.com', 'required' => true]); ?>
    <?php $ui->partial('components.field', ['name' => 'password', 'label' => 'Kata sandi', 'type' => 'password', 'autocomplete' => $register ? 'new-password' : 'current-password', 'placeholder' => 'Masukkan kata sandi', 'required' => true]); ?>
    <?php if ($register): ?>
    <?php $ui->partial('components.field', ['name' => 'password_confirmation', 'label' => 'Konfirmasi kata sandi', 'type' => 'password', 'autocomplete' => 'new-password', 'placeholder' => 'Ulangi kata sandi', 'required' => true]); ?>
    <?php endif; ?>
    <button class="button button-primary button-full" type="submit"><?= $register ? 'Daftar' : 'Masuk' ?><?= $ui->icon('arrow') ?></button>
    <p class="form-feedback" role="status" tabindex="-1"></p>
</form>
<p class="auth-switch"><?= $register ? 'Sudah punya akun?' : 'Belum punya akun?' ?> <a href="<?= $e($ui->url($register ? 'login' : 'register')) ?>"><?= $register ? 'Masuk di sini' : 'Daftar sekarang' ?> <?= $ui->icon('arrow') ?></a></p>
