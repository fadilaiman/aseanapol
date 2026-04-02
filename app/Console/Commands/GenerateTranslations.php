<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;

class GenerateTranslations extends Command
{
    protected $signature = 'translate:generate
                            {--locale= : Only generate for this locale (e.g. th)}
                            {--force   : Overwrite existing translations}';

    protected $description = 'Auto-translate lang/en/*.php into all supported locales via Google Translate (free, cached)';

    /** Locales to generate. 'en' is the source — never translated. */
    private const LOCALES = ['ms', 'id', 'th', 'vi', 'km', 'lo', 'my', 'tl'];

    public function handle(TranslationService $translator): int
    {
        $locales     = $this->option('locale') ? [$this->option('locale')] : self::LOCALES;
        $force       = (bool) $this->option('force');
        $sourceFiles = glob(base_path('lang/en/*.php')) ?: [];

        if (empty($sourceFiles)) {
            $this->error('No source files found in lang/en/');
            return 1;
        }

        foreach ($sourceFiles as $sourceFile) {
            $fileName = basename($sourceFile);
            $source   = require $sourceFile;

            $this->info("\nSource: lang/en/{$fileName} (" . $this->countKeys($source) . ' keys)');

            foreach ($locales as $locale) {
                $this->generateFile($translator, $locale, $fileName, $source, $force);
            }
        }

        $translator->flush();
        $this->newLine();
        $this->info('Done. Translation cache saved to storage/app/translation_cache.json');

        return 0;
    }

    private function generateFile(
        TranslationService $translator,
        string $locale,
        string $fileName,
        array $source,
        bool $force
    ): void {
        $targetPath = base_path("lang/{$locale}/{$fileName}");

        // Load existing translations so we can skip already-translated keys
        $existing = [];
        if (! $force && file_exists($targetPath)) {
            $existing = require $targetPath;
        }

        $translated  = [];
        $newCount    = 0;
        $skipCount   = 0;

        foreach ($source as $key => $value) {
            if (is_array($value)) {
                // Nested array — translate each sub-key
                $translated[$key] = [];
                foreach ($value as $subKey => $subValue) {
                    if (! $force && isset($existing[$key][$subKey])) {
                        $translated[$key][$subKey] = $existing[$key][$subKey];
                        $skipCount++;
                    } else {
                        $translated[$key][$subKey] = $translator->translate((string) $subValue, $locale);
                        usleep(250_000); // 250 ms throttle
                        $newCount++;
                    }
                }
            } else {
                if (! $force && isset($existing[$key])) {
                    $translated[$key] = $existing[$key];
                    $skipCount++;
                } else {
                    $translated[$key] = $translator->translate((string) $value, $locale);
                    usleep(250_000); // 250 ms throttle
                    $newCount++;
                }
            }
        }

        // Flush cache to disk after each locale file
        $translator->flush();

        // Ensure directory exists
        if (! is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        file_put_contents($targetPath, $this->renderPhpFile($translated));

        $this->line(sprintf(
            '  <info>%s</info>  lang/%s/%s — %d translated, %d kept from cache',
            str_pad(strtoupper($locale), 4),
            $locale,
            $fileName,
            $newCount,
            $skipCount
        ));
    }

    private function renderPhpFile(array $data): string
    {
        return "<?php\n\nreturn [\n" . $this->renderArray($data, 1) . "];\n";
    }

    private function renderArray(array $data, int $depth): string
    {
        $pad   = str_repeat('    ', $depth);
        $lines = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $lines[] = $pad . var_export($key, true) . ' => [';
                $lines[] = $this->renderArray($value, $depth + 1);
                $lines[] = $pad . '],';
            } else {
                $lines[] = $pad . var_export($key, true) . ' => ' . var_export($value, true) . ',';
            }
        }

        return implode("\n", $lines) . "\n";
    }

    private function countKeys(array $arr): int
    {
        $count = 0;
        array_walk_recursive($arr, function () use (&$count) { $count++; });
        return $count;
    }
}
