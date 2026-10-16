import { defineConfig } from '@playwright/test';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
const root = path.dirname(fileURLToPath(import.meta.url)).replaceAll('\\', '/');
const php = process.env.PHP_BINARY || 'C:/xampp/php/php.exe';

export default defineConfig({
  testDir: './tests/browser',
  outputDir: './test-results/browser-artifacts',
  workers: 1,
  timeout: 60000,
  expect: { timeout: 7000 },
  reporter: 'list',
  use: {
    baseURL: 'http://127.0.0.1:8080',
    viewport: { width: 1440, height: 1000 },
    launchOptions: { executablePath: process.env.CHROME_PATH || 'C:/Program Files/Google/Chrome/Application/chrome.exe' },
    screenshot: 'only-on-failure',
    trace: 'retain-on-failure',
  },
  webServer: {
    command: `"${php}" -S 127.0.0.1:8080 -t "${root}/public"`,
    url: 'http://127.0.0.1:8080/masuk',
    reuseExistingServer: true,
    timeout: 15000,
  },
});
