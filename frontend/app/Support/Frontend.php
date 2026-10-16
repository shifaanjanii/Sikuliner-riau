<?php
declare(strict_types=1);

namespace App\Support;

/** Presentation only. Authentication, authorization and persistence belong to Laravel. */
final class Frontend
{
    public array $data;
    public array $options;
    private string $views;

    public function __construct(array $data = [], array $options = [])
    {
        $this->data = array_replace([
            'regions' => [], 'foods' => [], 'featured' => [], 'categories' => [],
            'user' => null, 'errors' => [], 'old' => [], 'status' => null,
            'q' => '', 'region' => '', 'page' => 1, 'total' => 0, 'pages' => 1,
        ], $data);
        $this->options = array_replace([
            'base' => '', 'assetBase' => '/assets', 'demo' => false,
            'adminPreview' => false, 'connected' => false, 'csrf' => '',
            'uploadMaxBytes' => null, 'uploadAccept' => null, 'fieldMap' => [],
            'urlResolver' => null, 'queryMap' => [], 'updateMethod' => 'PUT',
        ], $options);
        $this->views = dirname(__DIR__, 2) . '/resources/views/';
    }

    public static function escape($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function asset(string $path): string
    {
        return rtrim($this->options['assetBase'], '/') . '/' . ltrim($path, '/');
    }

    public function url(string $name, array $params = [], array $query = []): string
    {
        if (is_callable($this->options['urlResolver'])) {
            $url = ($this->options['urlResolver'])($name, $params);
        } else {
            $routes = [
                'home' => '/', 'catalog' => '/kuliner', 'about' => '/tentang',
                'login' => '/masuk', 'register' => '/daftar', 'logout' => '/keluar',
                'login.store' => '/masuk', 'register.store' => '/daftar',
                'food.show' => '/kuliner/{id}', 'admin.home' => '/admin',
                'regions.index' => '/admin/wilayah', 'regions.create' => '/admin/wilayah/tambah',
                'regions.edit' => '/admin/wilayah/{id}/edit', 'regions.store' => '/admin/wilayah',
                'regions.update' => '/admin/wilayah/{id}', 'regions.destroy' => '/admin/wilayah/{id}',
                'foods.index' => '/admin/makanan', 'foods.create' => '/admin/makanan/tambah',
                'foods.edit' => '/admin/makanan/{id}/edit', 'foods.store' => '/admin/makanan',
                'foods.update' => '/admin/makanan/{id}', 'foods.destroy' => '/admin/makanan/{id}',
            ];
            if (!isset($routes[$name])) {
                throw new \InvalidArgumentException('Unknown UI route: ' . $name);
            }
            $path = $routes[$name];
            foreach ($params as $key => $value) {
                $path = str_replace('{' . $key . '}', rawurlencode((string) $value), $path);
            }
            if (strpos($path, '{') !== false) {
                throw new \InvalidArgumentException('Missing UI route parameter: ' . $name);
            }
            if ($this->options['adminPreview'] && strpos($path, '/admin') === 0) {
                $path = '/preview' . $path;
            }
            $url = $this->options['base'] . $path;
        }
        if ($this->options['demo']) {
            $query = array_merge(['demo' => '1'], $query);
        }
        $query = array_filter($query, static fn($value) => $value !== '' && $value !== null);
        $mappedQuery = [];
        foreach ($query as $key => $value) {
            $mappedQuery[$this->queryName($key)] = $value;
        }
        return $url . ($mappedQuery ? (strpos($url, '?') === false ? '?' : '&') . http_build_query($mappedQuery) : '');
    }

    public function partial(string $viewName, array $variables = []): void
    {
        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $viewName)) {
            throw new \InvalidArgumentException('Invalid view name.');
        }
        $file = $this->views . str_replace('.', '/', $viewName) . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException('Missing view: ' . $viewName);
        }
        $ui = $this;
        $d = $this->data;
        $e = [self::class, 'escape'];
        extract($variables, EXTR_SKIP);
        require $file;
    }

    public function render(string $view, array $variables = [], string $layout = 'public'): string
    {
        ob_start();
        $this->partial($view, $variables);
        $content = ob_get_clean();
        ob_start();
        $this->partial('layouts.' . $layout, array_merge($variables, ['content' => $content]));
        return ob_get_clean();
    }

    public function field(string $entity, string $field): string
    {
        return $this->options['fieldMap'][$entity][$field] ?? $field;
    }

    public function queryName(string $key): string
    {
        return $this->options['queryMap'][$key] ?? $key;
    }

    public function old(string $field, $fallback = ''): string
    {
        return self::escape($this->data['old'][$field] ?? $fallback);
    }

    public function error(string $field): string
    {
        $error = $this->data['errors'][$field] ?? '';
        return is_array($error) ? (string) ($error[0] ?? '') : (string) $error;
    }

    public function csrf(): string
    {
        // Laravel adapter must inject csrf_field(); never generate an authentication token here.
        return $this->options['csrf'];
    }

    public function regionName($id): string
    {
        foreach ($this->data['regions'] as $region) {
            if ((string) $region['id'] === (string) $id) {
                return $region['name'];
            }
        }
        return 'Asal daerah belum dicantumkan';
    }

    public function photo(?string $path): string
    {
        if (!$path) {
            return $this->asset('images/food-placeholder.svg');
        }
        // Only relative asset paths or http(s) URLs supplied by the backend are accepted.
        if (preg_match('~^https?://~i', $path) || (strpos($path, '/') === 0 && strpos($path, '//') !== 0)) {
            return $path;
        }
        if ($path[0] !== '/' && preg_match('~^[a-zA-Z0-9_./-]+$~', $path) && strpos($path, '..') === false) {
            return $this->asset($path);
        }
        return $this->asset('images/food-placeholder.svg');
    }

    public function icon(string $name, string $class = ''): string
    {
        $paths = [
            'arrow' => '<path d="M4 12h16m-6-6 6 6-6 6"/>',
            'arrow-left' => '<path d="M20 12H4m6-6-6 6 6 6"/>',
            'chevron' => '<path d="m8 4 8 8-8 8"/>',
            'down' => '<path d="m6 9 6 6 6-6"/>',
            'search' => '<circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/>',
            'pin' => '<path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
            'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
            'close' => '<path d="m6 6 12 12M18 6 6 18"/>',
            'eye' => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
            'eye-off' => '<path d="m3 3 18 18M10.5 5.1A12 12 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.2M6.4 6.4A19 19 0 0 0 2 12s3.5 7 10 7a12 12 0 0 0 5.6-1.6M10 10a3 3 0 0 0 4 4"/>',
            'home' => '<path d="m3 10 9-7 9 7v11h-7v-7h-4v7H3Z"/>',
            'grid' => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
            'food' => '<path d="M4 3v6a3 3 0 0 0 6 0V3M7 3v18M20 21V3c-4 1-5 6-5 10h5"/>',
            'leaf' => '<path d="M20 3C7 2 2 7 4 14c2 7 12 7 15-2 1-3 1-6 1-9ZM5 19l10-10"/>',
            'logout' => '<path d="M10 4H4v16h6m5-13 5 5-5 5M9 12h11"/>',
            'external' => '<path d="M14 3h7v7m0-7L10 14M10 3H3v18h18v-7"/>',
            'plus' => '<path d="M12 4v16M4 12h16"/>',
            'edit' => '<path d="m15 4 5 5M4 20l5-1L21 7a2 2 0 0 0-5-5L4 14Z"/>',
            'trash' => '<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/>',
            'upload' => '<path d="M12 16V3m-5 5 5-5 5 5M4 15v6h16v-6"/>',
            'image' => '<rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8" cy="8" r="1.5"/><path d="m3 17 5-5 4 4 4-6 5 7"/>',
            'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6m0-11v1"/>',
            'check' => '<path d="m5 12 4 4L19 6"/>',
            'book' => '<path d="M12 5v16M3 3c4-1 7 0 9 2 2-2 5-3 9-2v16c-4-1-7 0-9 2-2-2-5-3-9-2Z"/>',
            'spark' => '<path d="m12 3 2.5 6.5L21 12l-6.5 2.5L12 21l-2.5-6.5L3 12l6.5-2.5Z"/>',
            'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/>',
        ];
        return '<svg class="icon ' . self::escape($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? $paths['info']) . '</svg>';
    }
}
