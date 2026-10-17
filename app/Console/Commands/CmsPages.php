<?php

namespace App\Console\Commands;

use App\Support\Cms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class CmsPages extends Command
{
    protected $signature = 'cms:pages {--json : Output machine-readable JSON}';
    protected $description = 'List every page registered in config/cms_pages/*.php';

    public function handle(): int
    {
        $out = [];
        foreach (Cms::allGroups() as $g => $group) {
            foreach ($group['pages'] as $slug => $p) {
                $url = null;
                if (!empty($p['url'])) {
                    $url = url($p['url']);
                } elseif (!empty($p['route']) && Route::has($p['route'])) {
                    try {
                        $url = route($p['route'], array_merge(['locale' => 'en'], $p['route_params'] ?? []), false);
                    } catch (\Throwable) {
                    }
                }
                $out[] = [
                    'key' => "$g.$slug", 'label' => $p['label'], 'group' => $group['_group']['label'],
                    'url' => $url, 'sections' => array_keys($p['sections']),
                    'toggles' => array_keys(array_filter($p['sections'], fn ($s) => !empty($s['toggle']) && array_filter($s['fields'], fn ($f) => ($f['audit'] ?? true) !== false))), // a toggle whose fields are all audit=>false is state-only
                    'schema' => array_map(fn ($s) => array_map(fn ($f) => [
                        'type' => $f['type'] ?? 'text', 'skip' => ($f['audit'] ?? true) === false,
                        'sub'  => isset($f['fields']) ? array_map(fn ($x) => $x['type'] ?? 'text', $f['fields']) : null,
                        'subskip' => isset($f['fields']) ? array_keys(array_filter($f['fields'], fn ($x) => ($x['audit'] ?? true) === false)) : [],
                        'rows' => isset($f['fields']) ? count((array) ($f['default'] ?? [])) : null,
                    ], $s['fields']), $p['sections']),
                ];
            }
        }

        if ($this->option('json')) {
            $this->line(json_encode($out, JSON_UNESCAPED_SLASHES));
        } else {
            foreach ($out as $r) {
                $this->line(sprintf('%-40s %-34s %s', $r['key'], $r['label'], $r['url'] ?? '(no url)'));
            }
            $this->info(count($out) . ' page(s)');
        }

        return self::SUCCESS;
    }
}
