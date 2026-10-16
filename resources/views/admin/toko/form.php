<?php
$nameField      = $ui->field('shops', 'name');
$regionField    = $ui->field('shops', 'region_id');
$alamatField    = $ui->field('shops', 'alamat');
$teleponField   = $ui->field('shops', 'telepon');
$jamBukaField   = $ui->field('shops', 'jam_buka');
$deskripsiField = $ui->field('shops', 'deskripsi');
$photoField     = $ui->field('shops', 'photo');
$latField       = $ui->field('shops', 'latitude');
$lngField       = $ui->field('shops', 'longitude');
$mapsField      = $ui->field('shops', 'maps_url');
$formAction     = $ui->url('shops' . ($edit ? '.update' : '.store'), $edit ? ['id' => $item['id']] : []);
$maxBytes       = $ui->options['uploadMaxBytes'];
$accept         = $ui->options['uploadAccept'];
?>
<a class="back-link" href="<?= $e($ui->url('shops.index')) ?>"><?= $ui->icon('arrow-left') ?> Kembali ke Direktori Toko</a>
<div class="admin-heading"><div><h1><?= $e($title) ?></h1><p><?= $edit ? 'Perbarui informasi toko agar wisatawan mendapatkan data yang akurat.' : 'Daftarkan toko atau rumah makan penjual kuliner khas.' ?></p></div></div>
<form action="<?= $e($formAction) ?>" method="post" enctype="multipart/form-data" class="edit-form" data-validate <?= !$ui->options['connected'] ? 'data-preview-form' : '' ?>>
    <?= $ui->csrf() ?><?php if ($edit): ?><input type="hidden" name="_method" value="<?= $e($ui->options['updateMethod']) ?>"><?php endif; ?>
    <section class="form-panel"><h2>Informasi toko</h2><div class="form-stack">
        <?php $ui->partial('components.field', ['name' => $nameField, 'type' => 'text', 'label' => 'Nama toko / rumah makan', 'placeholder' => 'Contoh: Rumah Makan Pak Udin', 'value' => $item['name'] ?? '', 'required' => true]); ?>
        <div class="field"><label for="shop-region">Kabupaten/Kota <span class="required" aria-hidden="true">*</span></label><select id="shop-region" name="<?= $e($regionField) ?>" required aria-describedby="shop-region-error" <?= $ui->error($regionField) ? 'aria-invalid="true"' : '' ?>><option value="">Pilih kabupaten/kota</option><?php foreach ($d['regions'] as $region): ?><option value="<?= $e($region['id']) ?>" <?= $ui->old($regionField, $item['region_id'] ?? '') === $e($region['id']) ? 'selected' : '' ?>><?= $e($region['name']) ?></option><?php endforeach; ?></select><p id="shop-region-error" class="field-error" data-error-for="<?= $e($regionField) ?>" aria-live="polite"><?= $e($ui->error($regionField)) ?></p></div>
        <?php $ui->partial('components.field', ['name' => $alamatField, 'type' => 'text', 'label' => 'Alamat lengkap', 'placeholder' => 'Jl. Contoh No. 1, Kelurahan, Kecamatan', 'value' => $item['alamat'] ?? '', 'required' => true]); ?>
        <div class="form-two-columns">
            <?php $ui->partial('components.field', ['name' => $teleponField, 'type' => 'tel', 'label' => 'Nomor telepon', 'placeholder' => '0812-xxxx-xxxx', 'value' => $item['telepon'] ?? '', 'required' => false]); ?>
            <?php $ui->partial('components.field', ['name' => $jamBukaField, 'type' => 'text', 'label' => 'Jam operasional', 'placeholder' => 'Mis: 07.00–21.00 WIB', 'value' => $item['jam_buka'] ?? '', 'required' => false]); ?>
        </div>
        <div class="field"><label for="shop-deskripsi">Deskripsi toko</label><textarea id="shop-deskripsi" name="<?= $e($deskripsiField) ?>" rows="4" placeholder="Ceritakan keunggulan toko, spesialisasi kuliner, atau suasananya…" aria-describedby="deskripsi-error" <?= $ui->error($deskripsiField) ? 'aria-invalid="true"' : '' ?>><?= $ui->old($deskripsiField, $item['deskripsi'] ?? '') ?></textarea><p class="field-error" id="deskripsi-error" data-error-for="<?= $e($deskripsiField) ?>" aria-live="polite"><?= $e($ui->error($deskripsiField)) ?></p></div>
    </div></section>
    <section class="form-panel"><h2>Lokasi &amp; peta</h2><div class="form-stack">
        <p class="form-help">Isi koordinat agar peta navigasi tersedia bagi pengunjung. Atau sertakan tautan Google Maps langsung.</p>
        <div class="form-two-columns">
            <?php $ui->partial('components.field', ['name' => $latField, 'type' => 'text', 'label' => 'Latitude', 'placeholder' => 'Mis: 0.5070677', 'value' => $item['latitude'] ?? '', 'required' => false]); ?>
            <?php $ui->partial('components.field', ['name' => $lngField, 'type' => 'text', 'label' => 'Longitude', 'placeholder' => 'Mis: 101.4478888', 'value' => $item['longitude'] ?? '', 'required' => false]); ?>
        </div>
        <?php $ui->partial('components.field', ['name' => $mapsField, 'type' => 'url', 'label' => 'Tautan Google Maps', 'placeholder' => 'https://maps.google.com/...', 'value' => $item['maps_url'] ?? '', 'required' => false]); ?>
    </div></section>
    <aside class="form-panel" data-upload-panel><h2>Foto toko</h2>
        <img class="upload-preview" data-upload-preview src="<?= $e($ui->photo($item['photo'] ?? null)) ?>" alt="<?= $edit ? 'Foto saat ini' : 'Pratinjau foto yang dipilih' ?>" width="500" height="400">
        <div class="upload-zone"><?= $ui->icon('upload') ?><label for="shop-photo-input">Pilih foto dari perangkat</label><input id="shop-photo-input" type="file" name="<?= $e($photoField) ?>" accept="<?= $e(is_array($accept) ? implode(',', $accept) : 'image/*') ?>" data-upload data-accept="<?= $e(is_array($accept) ? implode(',', $accept) : '') ?>" <?= $maxBytes ? 'data-max-bytes="' . (int) $maxBytes . '"' : '' ?> aria-describedby="shop-photo-hint shop-photo-error" <?= $ui->error($photoField) ? 'aria-invalid="true"' : '' ?>><p class="upload-filename" data-upload-name></p><button type="button" class="upload-clear" data-upload-clear hidden>Batalkan foto baru</button></div>
        <p id="shop-photo-hint" class="field-hint"><?= $edit ? 'Kosongkan untuk mempertahankan foto saat ini. ' : '' ?><?= $accept ? $e(strtoupper(str_replace('image/', '', implode(', ', $accept)))) . '. ' : '' ?><?= $maxBytes ? 'Maksimal ' . $e($maxBytes / 1024 / 1024) . ' MB.' : '' ?></p>
        <p class="field-error" id="shop-photo-error" data-error-for="<?= $e($photoField) ?>" aria-live="polite"><?= $e($ui->error($photoField)) ?></p>
        <div class="notice notice-soft" style="margin-top:25px;margin-bottom:0"><?= $ui->icon('info') ?><span>Foto toko membantu wisatawan mengenali tempat sebelum berkunjung.</span></div>
    </aside>
    <div class="form-actions"><a class="button button-outline" href="<?= $e($ui->url('shops.index')) ?>">Batal</a><button type="submit" class="button button-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah Toko' ?><?= $ui->icon('check') ?></button></div>
    <p class="form-feedback" style="grid-column:1/-1" role="status" tabindex="-1"></p>
</form>
