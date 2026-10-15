<?php
declare(strict_types=1);

/** Development-only filtering. Production controller supplies its own filtered paginator. */
function previewCatalog(array $foods, string $q, string $region, int $page, int $perPage = 6): array
{
    $filtered = array_values(array_filter($foods, static function ($food) use ($q, $region) {
        return ($region === '' || (string) ($food['region_id'] ?? '') === $region)
            && ($q === '' || stripos($food['name'], $q) !== false);
    }));
    $total = count($filtered);
    $pages = max(1, (int) ceil($total / $perPage));
    $page = min(max(1, $page), $pages);
    return ['foods' => array_slice($filtered, ($page - 1) * $perPage, $perPage), 'total' => $total, 'page' => $page, 'pages' => $pages];
}
