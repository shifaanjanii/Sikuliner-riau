import sharp from 'sharp';
import { mkdir, copyFile, stat } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
for (const name of ['gulai-patin', 'bolu-kemojo', 'mie-sagu', 'lopek-bugi', 'lempuk-durian']) {
  const source = path.join(root, 'public/assets/images', name + '.png');
  const output = path.join(root, 'public/assets/images', name + '.webp');
  await sharp(source).resize({ width: 960, withoutEnlargement: true }).webp({ quality: 86, alphaQuality: 100 }).toFile(output);
  console.log(`${name}: ${Math.round((await stat(source)).size / 1024)} KB PNG → ${Math.round((await stat(output)).size / 1024)} KB WebP`);
}
await mkdir(path.join(root, 'public/assets/fonts'), { recursive: true });
for (const family of ['dm-sans', 'manrope']) {
  for (const weight of [400, 500, 600, 700, ...(family === 'manrope' ? [800] : [])]) {
    const filename = `${family}-latin-${weight}-normal.woff2`;
    await copyFile(path.join(root, 'node_modules/@fontsource', family, 'files', filename), path.join(root, 'public/assets/fonts', filename));
  }
  await copyFile(path.join(root, 'node_modules/@fontsource', family, 'LICENSE'), path.join(root, 'public/assets/fonts', family + '-LICENSE.txt'));
}
