<?php
$isFood = $entity === 'foods';
$label = $isFood ? 'Makanan' : 'Wilayah';
$nameField = $ui->field($entity, 'name');
$descriptionField = $ui->field($entity, 'description');
$regionField = $ui->field($entity, 'region_id');
$categoryField = $ui->field($entity, 'category');
$photoField = $ui->field($entity, 'photo');
$formAction = $ui->url($entity . ($edit ? '.update' : '.store'), $edit ? ['id' => $item['id']] : []);
$maxBytes = $ui->options['uploadMaxBytes'];
$accept = $ui->options['uploadAccept'];
?>
<a class="back-link" href="<?= $e($ui->url($entity . '.index')) ?>"><?= $ui->icon('arrow-left') ?> Kembali ke <?= $isFood ? 'Makanan Khas' : 'Kabupaten/Kota' ?></a>
<div class="admin-heading"><div><h1><?= $e($title) ?></h1><p><?= $edit ? 'Perbarui informasi agar tetap lengkap dan mudah dikenali.' : 'Tambahkan cerita baru ke SiKuliner Riau.' ?></p></div></div>
<form action="<?= $e($formAction) ?>" method="post" enctype="multipart/form-data" class="edit-form" data-validate <?= !$ui->options['connected'] ? 'data-preview-form' : '' ?>>
    <?= $ui->csrf() ?><?php if ($edit): ?><input type="hidden" name="_method" value="<?= $e($ui->options['updateMethod']) ?>"><?php endif; ?>
    <section class="form-panel"><h2>Informasi <?= strtolower($label) ?></h2><div class="form-stack">
        <?php $ui->partial('components.field', ['name' => $nameField, 'type' => 'text', 'label' => $isFood ? 'Nama makanan' : 'Nama kabupaten/kota', 'placeholder' => $isFood ? 'Nama makanan khas' : 'Kabupaten/Kota …', 'value' => $item['name'] ?? '', 'required' => true]); ?>
        <?php if ($isFood): ?>
        <div class="form-two-columns">
            <div class="field"><label for="food-region">Asal kabupaten/kota <span class="required" aria-hidden="true">*</span></label><select id="food-region" name="<?= $e($regionField) ?>" required aria-describedby="food-region-error" <?= $ui->error($regionField) ? 'aria-invalid="true"' : '' ?>><option value="">Pilih daerah asal</option><?php foreach ($d['regions'] as $region): ?><option value="<?= $e($region['id']) ?>" <?= $ui->old($regionField, $item['region_id'] ?? '') === $e($region['id']) ? 'selected' : '' ?>><?= $e($region['name']) ?></option><?php endforeach; ?></select><p id="food-region-error" class="field-error" data-error-for="<?= $e($regionField) ?>" aria-live="polite"><?= $e($ui->error($regionField)) ?></p></div>
            <div class="field"><label for="food-category">Kategori <span class="required" aria-hidden="true">*</span></label><select id="food-category" name="<?= $e($categoryField) ?>" required aria-describedby="food-category-error" <?= $ui->error($categoryField) ? 'aria-invalid="true"' : '' ?>><option value="">Pilih kategori</option><?php foreach ($d['categories'] as $category): ?><option value="<?= $e($category) ?>" <?= $ui->old($categoryField, $item['category'] ?? '') === $e($category) ? 'selected' : '' ?>><?= $e($category) ?></option><?php endforeach; ?></select><p id="food-category-error" class="field-error" data-error-for="<?= $e($categoryField) ?>" aria-live="polite"><?= $e($ui->error($categoryField)) ?></p></div>
        </div>
        <?php endif; ?>
        <div class="field"><label for="entity-description"><?= $isFood ? 'Sejarah / deskripsi' : 'Deskripsi budaya atau kuliner daerah' ?> <span class="required" aria-hidden="true">*</span></label><textarea id="entity-description" name="<?= $e($descriptionField) ?>" rows="7" placeholder="<?= $isFood ? 'Ceritakan makanan, cita rasa, dan sejarahnya…' : 'Ceritakan hal menarik tentang daerah ini…' ?>" required aria-describedby="description-hint description-error" <?= $ui->error($descriptionField) ? 'aria-invalid="true"' : '' ?>><?= $ui->old($descriptionField, $item['description'] ?? '') ?></textarea><p class="field-hint" id="description-hint">Gunakan informasi yang sudah diverifikasi. Jangan menebak asal atau sejarah makanan.</p><p class="field-error" id="description-error" data-error-for="<?= $e($descriptionField) ?>" aria-live="polite"><?= $e($ui->error($descriptionField)) ?></p></div>
    </div></section>
    <aside class="form-panel" data-upload-panel><h2><?= $isFood ? 'Foto makanan' : 'Gambar wilayah' ?></h2>
        <?php if ($isFood || !empty($ui->options['regionPhotoEnabled'])): ?>
        <img class="upload-preview" data-upload-preview src="<?= $e($ui->photo($item['photo'] ?? null)) ?>" alt="<?= $edit ? 'Foto saat ini' : 'Pratinjau foto yang dipilih' ?>" width="500" height="400">
        <div class="upload-zone"><?= $ui->icon('upload') ?><label for="photo-input">Pilih foto dari perangkat</label><input id="photo-input" type="file" name="<?= $e($photoField) ?>" accept="<?= $e(is_array($accept) ? implode(',', $accept) : 'image/*') ?>" data-upload data-accept="<?= $e(is_array($accept) ? implode(',', $accept) : '') ?>" <?= $maxBytes ? 'data-max-bytes="' . (int) $maxBytes . '"' : '' ?> aria-describedby="photo-hint photo-error" <?= $ui->error($photoField) ? 'aria-invalid="true"' : '' ?>><p class="upload-filename" data-upload-name></p><button type="button" class="upload-clear" data-upload-clear hidden>Batalkan foto baru</button></div>
        <p id="photo-hint" class="field-hint"><?= $edit ? 'Kosongkan untuk mempertahankan foto saat ini. ' : '' ?><?= $ui->options['adminPreview'] ? 'Acuan pratinjau: ' : '' ?><?= $accept ? $e(strtoupper(str_replace('image/', '', implode(', ', $accept)))) . '. ' : '' ?><?= $maxBytes ? 'Maksimal ' . $e($maxBytes / 1024 / 1024) . ' MB.' : 'Pilih foto yang jelas. Ketentuan ukuran dan format mengikuti layanan unggah saat tersedia.' ?><?= $ui->options['adminPreview'] ? ' Ketentuan akhir mengikuti validasi server.' : '' ?></p><p class="field-error" id="photo-error" data-error-for="<?= $e($photoField) ?>" aria-live="polite"><?= $e($ui->error($photoField)) ?></p>
        <?php else: ?><p class="form-help">Gambar wilayah dapat ditambahkan apabila tersedia dalam data wilayah.</p><?php endif; ?>
        <div class="notice notice-soft" style="margin-top:25px;margin-bottom:0"><?= $ui->icon('info') ?><span><?= $isFood ? 'Foto membantu pengunjung mengenal makanan. Pastikan foto sesuai dengan informasi yang ditulis.' : 'Tuliskan nama wilayah secara lengkap agar mudah ditemukan pengunjung.' ?></span></div>
    </aside>
    <div class="form-actions"><a class="button button-outline" href="<?= $e($ui->url($entity . '.index')) ?>">Batal</a><button type="submit" class="button button-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah ' . $label ?><?= $ui->icon('check') ?></button></div>
    <p class="form-feedback" style="grid-column:1/-1" role="status" tabindex="-1"></p>
</form>
