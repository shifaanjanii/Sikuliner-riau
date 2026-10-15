import { test } from 'node:test';
import assert from 'node:assert/strict';

const base = process.env.UI_BASE_URL || 'http://127.0.0.1:8080';
const pages = [
  '/', '/kuliner', '/tentang', '/masuk', '/daftar',
  '/?demo=1', '/kuliner?demo=1', '/kuliner/contoh-lopek?demo=1',
  '/preview/admin', '/preview/admin/wilayah', '/preview/admin/wilayah/tambah',
  '/preview/admin/wilayah/kampar/edit', '/preview/admin/makanan',
  '/preview/admin/makanan?demo=1', '/preview/admin/makanan/tambah',
  '/preview/admin/makanan/contoh-kemojo/edit?demo=1',
];

for (const path of pages) {
  test(`GET ${path} renders without PHP errors`, async () => {
    const response = await fetch(base + path);
    const html = await response.text();
    assert.equal(response.status, 200);
    assert.match(html, /<html lang="id">/);
    assert.doesNotMatch(html, /(?:Fatal error|Warning:|Parse error|Notice:|Uncaught Error)/);
    assert.match(html, /id="konten"/);
  });
}

test('the default public catalog never silently uses development data', async () => {
  const html = await (await fetch(base + '/kuliner')).text();
  assert.match(html, /Cerita rasa sedang disiapkan/);
  assert.doesNotMatch(html, /contoh-kemojo/);
});
test('demo data is visibly labeled and does not guess unknown origins', async () => {
  const html = await (await fetch(base + '/kuliner?demo=1')).text();
  assert.match(html, /Bukan katalog resmi/);
  assert.match(html, /Asal daerah belum dicantumkan/);
  assert.match(html, /noindex,nofollow/);
});
test('query and region combine, stay selected and match the source data', async () => {
  const html = await (await fetch(base + '/kuliner?demo=1&region=kampar&q=lopek')).text();
  assert.match(html, /value="kampar" selected/);
  assert.match(html, /value="lopek"/);
  assert.match(html, /contoh-lopek/);
  assert.doesNotMatch(html, /contoh-kemojo/);
});
test('no matches has a useful empty state and reset control', async () => {
  const html = await (await fetch(base + '/kuliner?demo=1&region=kampar&q=xyz')).text();
  assert.match(html, /Rasanya belum ketemu/);
  assert.match(html, /Reset filter/);
});
test('query values cannot inject markup', async () => {
  const html = await (await fetch(base + '/kuliner?q=' + encodeURIComponent('"><script>alert(1)</script>'))).text();
  assert.doesNotMatch(html, /<script>alert\(1\)<\/script>/);
  assert.match(html, /&lt;script&gt;/);
});
test('real admin URLs deny access without backend authorization', async () => {
  for (const path of ['/admin', '/admin/wilayah', '/admin/makanan/tambah']) {
    assert.equal((await fetch(base + path)).status, 403);
  }
});
test('writes cannot claim success, create accounts, or persist records in preview', async () => {
  for (const path of ['/masuk', '/daftar', '/keluar', '/preview/admin/makanan', '/preview/admin/wilayah/kampar']) {
    const response = await fetch(base + path, { method: 'POST', body: new URLSearchParams({ name: 'test', email: 'test@example.invalid', password: 'not-a-real-account' }) });
    assert.equal(response.status, 503);
    assert.match(await response.text(), /Tidak ada data yang disimpan/);
    assert.equal(response.headers.get('set-cookie'), null);
  }
});
test('unknown pages and records return 404', async () => {
  for (const path of ['/missing-page', '/kuliner/tidak-ada', '/preview/admin/wilayah/tidak-ada/edit', '/preview/admin/hilang']) {
    assert.equal((await fetch(base + path)).status, 404);
  }
});
test('local assets are available', async () => {
  for (const path of ['/assets/css/app.css', '/assets/js/app.js', '/assets/images/gulai-patin.png', '/assets/images/bolu-kemojo.png', '/assets/images/mie-sagu.png', '/assets/images/food-placeholder.svg']) {
    assert.equal((await fetch(base + path)).status, 200, path);
  }
});
