<?php $error = $ui->error($name); $id = 'field-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', $name); ?>
<div class="field">
    <label for="<?= $e($id) ?>"><?= $e($label) ?><?= !empty($required) ? ' <span class="required" aria-hidden="true">*</span>' : '' ?></label>
    <div class="input-wrap <?= $type === 'password' ? 'password-wrap' : '' ?>">
        <input id="<?= $e($id) ?>" name="<?= $e($name) ?>" type="<?= $e($type ?? 'text') ?>" value="<?= $type === 'password' ? '' : $ui->old($name, $value ?? '') ?>" placeholder="<?= $e($placeholder ?? '') ?>" autocomplete="<?= $e($autocomplete ?? 'off') ?>" <?= !empty($required) ? 'required' : '' ?> <?= $error ? 'aria-invalid="true"' : '' ?> aria-describedby="<?= $e($id) ?>-error" <?= isset($maxlength) ? 'maxlength="' . (int) $maxlength . '"' : '' ?>>
        <?php if ($type === 'password'): ?><button class="password-toggle" type="button" data-password-toggle="<?= $e($id) ?>" aria-controls="<?= $e($id) ?>" aria-pressed="false" aria-label="Tampilkan kata sandi"><?= $ui->icon('eye') ?><span class="sr-only">Tampilkan kata sandi</span></button><?php endif; ?>
    </div>
    <p class="field-error" id="<?= $e($id) ?>-error" data-error-for="<?= $e($name) ?>" aria-live="polite"><?= $e($error) ?></p>
</div>
