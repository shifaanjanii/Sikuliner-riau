<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/app/Support/Frontend.php';
require_once __DIR__ . '/catalog.php';

use App\Support\Frontend;

// Standalone preview deliberately has no database, sessions, accounts or write operations.
$base = PHP_SAPI === 'cli-server' ? '' : rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/.');
$requestPath = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$path = substr($requestPath, 0, strlen($base)) === $base ? substr($requestPath, strlen($base)) : $requestPath;
$path = '/' . trim($path, '/');
$adminPreview = strpos($path, '/preview/admin') === 0;
$demo = isset($_GET['demo']) && $_GET['demo'] === '1';
$content = require __DIR__ . '/content.php';
$allFoods = $demo ? require __DIR__ . '/fixtures.php' : [];
$q = trim(is_string($_GET['q'] ?? null) ? substr($_GET['q'], 0, 160) : '');
$region = is_string($_GET['region'] ?? null) ? $_GET['region'] : '';
if (!in_array($region, array_column($content['regions'], 'id'), true)) {
    $region = '';
}
$result = previewCatalog($allFoods, $q, $region, max(1, (int) ($_GET['page'] ?? 1)));
$ui = new Frontend(array_merge($content, $result, ['q' => $q, 'region' => $region]), [
    'base' => $base, 'assetBase' => $base . (defined('SIKULINER_ROOT_ENTRY') ? '/public/assets' : '/assets'),
    'demo' => $demo, 'adminPreview' => $adminPreview,
    'uploadMaxBytes' => 2 * 1024 * 1024,
    'uploadAccept' => ['image/jpeg', 'image/png', 'image/webp'],
]);
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
if ($demo || $adminPreview) {
    header('X-Robots-Tag: noindex, nofollow');
}
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET' && $method !== 'HEAD') {
    http_response_code(503);
    echo $ui->render('message', [
        'title' => 'Layanan belum tersedia', 'code' => 'Belum dapat diproses',
        'message' => 'Permintaan belum dapat diproses. Silakan kembali ke halaman sebelumnya. Tidak ada data yang disimpan.',
    ]);
    return;
}
if ($path === '/' || $path === '/index.php') {
    echo $ui->render('home', ['title' => 'Kenali Riau, lewat rasanya.', 'active' => 'home']);
} elseif ($path === '/kuliner') {
    echo $ui->render('catalog', ['title' => 'Jelajah Kuliner', 'active' => 'catalog']);
} elseif ($path === '/tentang') {
    echo $ui->render('about', ['title' => 'Tentang SiKuliner', 'active' => 'about']);
} elseif ($path === '/masuk' || $path === '/daftar') {
    $register = $path === '/daftar';
    echo $ui->render('auth.form', ['title' => $register ? 'Buat akun' : 'Selamat datang kembali', 'register' => $register], 'auth');
} elseif (preg_match('~^/kuliner/([^/]+)$~', $path, $match)) {
    $food = null;
    foreach ($allFoods as $item) {
        if ((string) $item['id'] === $match[1]) { $food = $item; break; }
    }
    if ($food) {
        echo $ui->render('detail', ['title' => $food['name'], 'food' => $food, 'active' => 'catalog']);
    } else {
        http_response_code(404);
        echo $ui->render('message', ['title' => 'Makanan belum ditemukan', 'code' => '404', 'message' => 'Cerita yang kamu cari belum tersedia. Yuk, temukan rasa lainnya di katalog.']);
    }
} elseif ($path === '/admin' || strpos($path, '/admin/') === 0) {
    http_response_code(403);
    echo $ui->render('message', ['title' => 'Halaman khusus pengelola', 'code' => 'Akses terbatas', 'message' => 'Halaman ini hanya dapat diakses oleh pengelola yang sudah masuk dan memiliki izin.']);
} elseif ($adminPreview) {
    $subpath = substr($path, strlen('/preview/admin')) ?: '/';
    if ($subpath === '/') {
        echo $ui->render('admin.home', ['title' => 'Beranda Admin', 'active' => 'admin.home'], 'admin');
    } elseif (preg_match('~^/(wilayah|makanan)(?:/(tambah|[^/]+/edit))?$~', $subpath, $match)) {
        $entity = $match[1] === 'wilayah' ? 'regions' : 'foods';
        $items = $entity === 'regions' ? $content['regions'] : $allFoods;
        $form = !empty($match[2]);
        $edit = $form && $match[2] !== 'tambah';
        $item = [];
        if ($edit) {
            $id = explode('/', $match[2])[0];
            foreach ($items as $candidate) {
                if ((string) $candidate['id'] === $id) { $item = $candidate; break; }
            }
        }
        if ($edit && !$item) {
            http_response_code(404);
            echo $ui->render('message', ['title' => 'Data belum ditemukan', 'code' => '404', 'message' => 'Data yang ingin diubah belum tersedia.']);
        } else {
            $label = $entity === 'regions' ? 'Wilayah' : 'Makanan';
            echo $ui->render($form ? 'admin.form' : 'admin.list', [
                'title' => $form ? ($edit ? 'Edit ' : 'Tambah ') . $label : ($entity === 'regions' ? 'Kabupaten/Kota' : 'Makanan Khas'),
                'active' => $entity . '.index', 'entity' => $entity, 'items' => $items, 'item' => $item, 'edit' => $edit,
            ], 'admin');
        }
    } else {
        http_response_code(404);
        echo $ui->render('message', ['title' => 'Halaman tidak ditemukan', 'code' => '404', 'message' => 'Halaman yang kamu cari belum tersedia.']);
    }
} else {
    http_response_code(404);
    echo $ui->render('message', ['title' => 'Halaman tidak ditemukan', 'code' => '404', 'message' => 'Sepertinya kamu mengambil jalan yang berbeda. Kembali ke beranda untuk melanjutkan jelajah.']);
}
