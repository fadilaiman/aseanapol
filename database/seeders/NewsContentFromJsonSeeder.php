<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsContentFromJsonSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('docs/aseanapol/exports/news_full.json');

        if (!file_exists($jsonPath)) {
            $this->command->error("news_full.json not found.");
            return;
        }

        $this->command->info('Streaming news_full.json…');

        $fh      = fopen($jsonPath, 'r');
        $updated = 0;
        $skipped = 0;
        $errors  = 0;
        $buf     = '';
        $depth   = 0;
        $inObj   = false;

        while (!feof($fh)) {
            $chunk = fread($fh, 65536); // 64 KB at a time
            $len   = strlen($chunk);

            for ($i = 0; $i < $len; $i++) {
                $c = $chunk[$i];

                if (!$inObj) {
                    if ($c === '{') {
                        $inObj = true;
                        $depth = 1;
                        $buf   = '{';
                    }
                    continue;
                }

                $buf .= $c;

                if ($c === '{') {
                    $depth++;
                } elseif ($c === '}') {
                    $depth--;
                    if ($depth === 0) {
                        // Full object collected — process it
                        $this->processRecord($buf, $updated, $skipped, $errors);
                        $buf   = '';
                        $inObj = false;
                    }
                }
            }
        }

        fclose($fh);

        $this->command->info("Done — updated: {$updated}, skipped (same/shorter): {$skipped}, errors: {$errors}");
    }

    private function processRecord(string $raw, int &$updated, int &$skipped, int &$errors): void
    {
        // Strip/escape control characters common in Sitefinity exports.
        // Literal newlines/CR inside JSON string values must be replaced with a space.
        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x0A\x0D]/', ' ', $raw);

        $record = json_decode($clean, true);

        if (!$record || empty($record['id'])) {
            $errors++;
            return;
        }

        $id      = $record['id'];
        $content = $record['content'] ?? null;

        if (empty($content)) {
            $skipped++;
            return;
        }

        // Only update if JSON content is longer than what's stored (avoids overwriting fixed content)
        $existing = DB::table('news_items')->where('id', $id)->value('content');

        if (strlen($content) <= strlen((string) $existing)) {
            $skipped++;
            return;
        }

        DB::table('news_items')->where('id', $id)->update(['content' => $content]);
        $updated++;
    }
}
