<?php
// Helper for security-attacks.mjs: dumps every CMS page definition + its public URL (path, locale placeholder).
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$out = [];
foreach (App\Support\Cms::allGroups() as $g => $grp) {
    foreach ($grp['pages'] as $slug => $def) {
        $url = null;
        if (!empty($def['url'])) {
            $url = $def['url'];
        } elseif (!empty($def['route']) && Illuminate\Support\Facades\Route::has($def['route'])) {
            try {
                $url = parse_url(route($def['route'], array_merge(['locale' => 'en'], $def['route_params'] ?? [])), PHP_URL_PATH);
                $url = preg_replace('#^/en(?=/|$)#', '/{locale}', $url);
            } catch (Throwable $e) {
            }
        }
        $out["$g.$slug"] = ['url' => $url, 'def' => ['sections' => $def['sections'] ?? []]];
    }
}
echo json_encode(['pages' => $out], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
