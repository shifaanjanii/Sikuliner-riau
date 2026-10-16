import { test, expect } from '@playwright/test';
import path from 'node:path';

test('desktop hero renders, changes food and respects the latest carousel action', async ({ page }) => {
  const errors = [];
  page.on('pageerror', error => errors.push(error.message));
  await page.goto('/?demo=1');
  await expect(page.getByRole('heading', { name: 'Kenali Riau, lewat rasanya.' })).toBeVisible();
  await expect(page.locator('[data-main-plate] img')).toHaveJSProperty('complete', true);
  await page.screenshot({ path: 'test-results/home-desktop.png', fullPage: true, animations: 'disabled' });
  await page.getByRole('button', { name: 'Makanan unggulan berikutnya' }).click();
  await expect(page.locator('[data-featured-name]')).toHaveText('Bolu Kemojo');
  await expect(page.locator('[data-main-plate] img')).toHaveAttribute('src', /bolu-kemojo/);
  await page.getByRole('button', { name: 'Makanan unggulan berikutnya' }).click();
  await page.getByRole('button', { name: 'Makanan unggulan sebelumnya' }).click();
  await expect(page.locator('[data-featured-name]')).toHaveText('Bolu Kemojo');
  expect(errors).toEqual([]);
});

test('login and registration validate beside fields without simulating authentication', async ({ page }) => {
  await page.goto('/masuk');
  await page.getByRole('button', { name: 'Masuk', exact: true }).click();
  await expect(page.locator('[data-error-for=email]')).toContainText('perlu diisi');
  await page.getByLabel('Email', { exact: true }).fill('not-an-email');
  await page.getByLabel('Kata sandi', { exact: true }).fill('ui-test-value');
  await page.getByRole('button', { name: 'Masuk', exact: true }).click();
  await expect(page.locator('[data-error-for=email]')).toContainText('email yang valid');
  await page.getByRole('button', { name: 'Tampilkan kata sandi', exact: true }).click();
  await expect(page.getByLabel('Kata sandi', { exact: true })).toHaveAttribute('type', 'text');
  await page.getByRole('button', { name: 'Sembunyikan kata sandi' }).click();
  await expect(page.getByLabel('Kata sandi', { exact: true })).toHaveAttribute('type', 'password');
  await page.getByLabel('Email', { exact: true }).fill('ui-test@example.invalid');
  await page.getByRole('button', { name: 'Masuk', exact: true }).click();
  await expect(page.locator('.form-feedback')).toContainText('Tidak ada proses masuk');
  await expect(page).toHaveURL(/\/masuk$/);
  await page.screenshot({ path: 'test-results/login-desktop.png', fullPage: true });
  await page.goto('/daftar');
  await page.getByLabel('Nama lengkap').fill('Pengujian UI');
  await page.getByLabel('Email', { exact: true }).fill('ui-test@example.invalid');
  await page.getByLabel('Kata sandi', { exact: true }).fill('ui-test-value');
  await page.getByLabel('Konfirmasi kata sandi', { exact: true }).fill('different');
  await page.getByRole('button', { name: 'Daftar', exact: true }).click();
  await expect(page.locator('[data-error-for=password_confirmation]')).toContainText('belum sama');
  await expect(page.locator('[name=role]')).toHaveCount(0);
});

test('catalog combines URL filters, survives refresh, and resets no results', async ({ page }) => {
  await page.goto('/kuliner?demo=1');
  await expect(page.locator('.food-card')).toHaveCount(4);
  await page.getByLabel('Nama makanan', { exact: true }).fill('lopek');
  await page.getByLabel('Kabupaten/Kota', { exact: true }).selectOption('kampar');
  await expect(page).toHaveURL(/region=kampar/);
  await expect(page).toHaveURL(/q=lopek/);
  await expect(page.locator('.food-card')).toHaveCount(1);
  await page.reload();
  await expect(page.getByLabel('Nama makanan', { exact: true })).toHaveValue('lopek');
  await expect(page.getByLabel('Kabupaten/Kota', { exact: true })).toHaveValue('kampar');
  await page.getByRole('link', { name: /Baca Selengkapnya/ }).click();
  await expect(page.getByRole('heading', { name: 'Lopek Bugi', exact: true })).toBeVisible();
  await page.getByRole('link', { name: 'Kembali ke Jelajah Kuliner' }).click();
  await expect(page.getByLabel('Nama makanan', { exact: true })).toHaveValue('lopek');
  await page.getByLabel('Nama makanan', { exact: true }).fill('xyz');
  await page.getByRole('button', { name: 'Cari Kuliner' }).click();
  await expect(page.getByRole('heading', { name: 'Rasanya belum ketemu.' })).toBeVisible();
  await page.getByRole('link', { name: 'Reset filter' }).click();
  await expect(page.locator('.food-card')).toHaveCount(4);
});

test('admin edit retains values, previews photos, and never claims to save', async ({ page }) => {
  await page.goto('/preview/admin/makanan/contoh-kemojo/edit?demo=1');
  await expect(page.getByLabel('Nama makanan', { exact: true })).toHaveValue('Bolu Kemojo');
  await expect(page.getByLabel('Kategori', { exact: true })).toHaveValue('Kue Tradisional');
  const oldPhoto = await page.locator('[data-upload-preview]').getAttribute('src');
  await page.getByLabel('Pilih foto dari perangkat').setInputFiles(path.resolve('public/assets/images/favicon.svg'));
  await expect(page.locator('#photo-error')).toContainText('format gambar');
  await page.getByLabel('Pilih foto dari perangkat').setInputFiles(path.resolve('public/assets/images/gulai-patin.webp'));
  await expect(page.locator('[data-upload-preview]')).toHaveAttribute('src', /^blob:/);
  await page.getByRole('button', { name: 'Batalkan foto baru' }).click();
  await expect(page.locator('[data-upload-preview]')).toHaveAttribute('src', oldPhoto);
  await page.getByLabel('Asal kabupaten/kota').selectOption('kampar');
  await page.getByRole('button', { name: 'Simpan Perubahan' }).click();
  await expect(page.locator('.form-feedback')).toContainText('belum dapat disimpan');
  await page.screenshot({ path: 'test-results/admin-form-desktop.png', fullPage: true });
});

test('delete confirmation supports cancel, Escape and preview feedback', async ({ page }) => {
  await page.goto('/preview/admin/wilayah');
  const remove = page.getByRole('button', { name: 'Hapus Kabupaten Kampar', exact: true });
  await remove.click();
  await expect(page.getByRole('dialog')).toBeVisible();
  await page.getByRole('button', { name: 'Batal', exact: true }).click();
  await expect(page.getByRole('dialog')).not.toBeVisible();
  await expect(page.locator('.form-feedback:visible')).toHaveCount(0);
  await remove.click();
  await page.keyboard.press('Escape');
  await expect(page.getByRole('dialog')).not.toBeVisible();
  await remove.click();
  await page.getByRole('button', { name: 'Ya, hapus' }).click();
  await expect(page.locator('.form-feedback:visible')).toContainText('belum dapat disimpan atau dihapus');
  await expect(page.getByText('Kabupaten Kampar', { exact: true }).first()).toBeVisible();
});

test('mobile public and admin menus work, with no page-wide horizontal overflow', async ({ page }) => {
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('/?demo=1');
  await page.getByRole('button', { name: 'Buka menu navigasi' }).click();
  await expect(page.locator('#public-menu')).toBeVisible();
  await page.getByRole('link', { name: 'Jelajah Kuliner', exact: true }).first().click();
  await expect(page).toHaveURL(/\/kuliner/);
  for (const url of ['/?demo=1', '/kuliner?demo=1', '/tentang', '/masuk', '/daftar', '/preview/admin', '/preview/admin/wilayah', '/preview/admin/makanan/contoh-kemojo/edit?demo=1']) {
    await page.goto(url);
    const dimensions = await page.evaluate(() => ({ width: document.documentElement.clientWidth, scroll: document.documentElement.scrollWidth }));
    expect(dimensions.scroll, url).toBeLessThanOrEqual(dimensions.width);
  }
  await page.getByRole('button', { name: 'Buka menu admin' }).click();
  await expect(page.locator('#admin-sidebar')).toHaveClass(/is-open/);
  await page.keyboard.press('Escape');
  await expect(page.locator('#admin-sidebar')).not.toHaveClass(/is-open/);
  await page.goto('/?demo=1');
  await page.screenshot({ path: 'test-results/home-mobile.png', fullPage: true, animations: 'disabled' });
});

test('reduced motion and JavaScript-free browsing keep content available', async ({ page, browser }) => {
  await page.emulateMedia({ reducedMotion: 'reduce' });
  await page.goto('/');
  expect(await page.locator('.plate-main').evaluate(el => parseFloat(getComputedStyle(el).animationDuration))).toBeLessThan(.02);
  await expect(page.getByRole('heading', { name: 'Kenali Riau, lewat rasanya.' })).toBeVisible();
  const context = await browser.newContext({ javaScriptEnabled: false });
  const nojs = await context.newPage();
  await nojs.goto('http://127.0.0.1:8080/kuliner?demo=1&region=kampar&q=lopek');
  await expect(nojs.locator('.food-card')).toHaveCount(1);
  await expect(nojs.getByRole('link', { name: /Baca Selengkapnya/ })).toBeVisible();
  await context.close();
});
