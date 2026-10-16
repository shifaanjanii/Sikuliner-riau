<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/Support/Frontend.php';
require dirname(__DIR__) . '/preview/catalog.php';
use App\Support\Frontend;

$count = 0;
function check(bool $result, string $message): void {
    global $count;
    if (!$result) { fwrite(STDERR, 'FAIL: ' . $message . PHP_EOL); exit(1); }
    $count++;
}
set_error_handler(static function ($severity, $message, $file, $line) { throw new ErrorException($message, 0, $severity, $file, $line); });
$content = require dirname(__DIR__) . '/preview/content.php';
$ui = new Frontend(array_merge($content, [
    'errors' => ['email' => ['Email tidak valid.']],
    'old' => ['email' => '"><script>alert(1)</script>', 'password' => 'NEVER-RENDER-PASSWORD'],
]));
$login = $ui->render('auth.form', ['title' => 'Masuk', 'register' => false], 'auth');
check(strpos($login, 'Email tidak valid.') !== false, 'Server validation is displayed.');
check(strpos($login, '&lt;script&gt;') !== false, 'Old input is HTML escaped.');
check(strpos($login, '<script>alert(1)</script>') === false, 'Old input cannot inject script.');
check(strpos($login, 'NEVER-RENDER-PASSWORD') === false, 'Passwords are never repopulated.');
$register = $ui->render('auth.form', ['title' => 'Daftar', 'register' => true], 'auth');
check(strpos($register, 'name="role"') === false, 'Public registration cannot select a role.');
$user = new Frontend(array_merge($content, ['user' => ['name' => '<Jane>', 'is_admin' => false]]), ['connected' => true, 'csrf' => '<input type="hidden" name="_token" value="test-token">']);
$html = $user->render('message', ['title' => 'Test', 'message' => 'Test']);
check(strpos($html, '&lt;Jane&gt;') !== false, 'Account name is escaped.');
check(strpos($html, 'action="/keluar" method="post"') !== false, 'Logout uses POST.');
check(strpos($html, 'value="test-token"') !== false, 'Backend CSRF field is rendered.');
check(strpos($html, 'href="/admin"') === false, 'Regular users see no admin shortcut.');
$user->data['user']['is_admin'] = true;
check(strpos($user->render('message', ['title' => 'Test', 'message' => 'Test']), 'href="/admin"') !== false, 'Backend admin capability enables the shortcut.');
$mapped = new Frontend([], ['urlResolver' => static fn($route, $params) => '/real/' . $route, 'queryMap' => ['q' => 'search', 'region' => 'kabupaten_id']]);
check($mapped->url('catalog', [], ['q' => 'a b', 'region' => '12']) === '/real/catalog?search=a+b&kabupaten_id=12', 'Query keys can match an existing backend.');
check($ui->photo('javascript:alert(1)') === '/assets/images/food-placeholder.svg', 'Unsafe image URLs are rejected.');
check($ui->photo('//untrusted.example/image') === '/assets/images/food-placeholder.svg', 'Protocol-relative image URLs are rejected.');
$fixtures = [
    ['name' => 'Makanan Uji A', 'region_id' => 'a'], ['name' => 'Makanan Uji B', 'region_id' => 'b'],
    ['name' => 'Kue Uji A', 'region_id' => 'a'],
];
check(previewCatalog($fixtures, 'makanan', 'a', 1)['total'] === 1, 'Preview query and region combine.');
check(previewCatalog($fixtures, 'tidak ada', '', 1)['total'] === 0, 'No results remains empty.');
$paging = previewCatalog($fixtures, '', 'a', 99, 1);
check($paging['page'] === 2 && $paging['total'] === 2 && $paging['foods'][0]['name'] === 'Kue Uji A', 'Pagination clamps safely and keeps filtering.');
echo $count . ' presentation checks passed.' . PHP_EOL;
