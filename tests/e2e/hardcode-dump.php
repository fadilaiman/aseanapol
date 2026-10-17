<?php
// Dumps every editable string (registry defaults + DB module text) to JSON. Usage: php tests/e2e/hardcode-dump.php out.json
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$out = [];
$walk = function ($v, $src) use (&$walk, &$out) {
    if (is_string($v)) { if (trim($v) !== '') $out[] = [$v, $src]; }
    elseif (is_array($v)) foreach ($v as $k => $x) $walk($x, $src);
};
foreach (glob(__DIR__ . '/../../config/cms_pages/*.php') as $f) {
    $walk(require $f, 'cfg:' . basename($f));
}
foreach (['news_items','news_item_translations','events','publications','newsletters','video_galleries','digital_library_collections','digital_library_items','gallery_images','cms_blocks'] as $t) {
    try {
        foreach (Illuminate\Support\Facades\DB::table($t)->get() as $row) {
            foreach ((array)$row as $c => $v) {
                if (is_string($v)) {
                    $d = json_decode($v, true);
                    if (is_array($d)) $walk($d, "db:$t"); else $walk($v, "db:$t");
                }
            }
        }
    } catch (Throwable $e) { fwrite(STDERR, "skip $t: " . $e->getMessage() . "\n"); }
}
file_put_contents($argv[1], json_encode($out, JSON_UNESCAPED_UNICODE));
echo count($out) . " strings\n";
