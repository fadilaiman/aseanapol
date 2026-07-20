<?php

namespace App\Jobs;

use App\Models\NewsItem;
use App\Models\NewsTranslation;
use App\Services\GatewayTranslator;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class TranslateNewsItemJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 900;
    public array $backoff = [60, 300];
    public int $uniqueFor = 3600;

    public function __construct(
        public string $newsItemId,
        public string $locale,
        public string $sourceHash,
    ) {}

    public function uniqueId(): string
    {
        return "{$this->newsItemId}:{$this->locale}";
    }

    public function handle(GatewayTranslator $translator): void
    {
        $item = NewsItem::find($this->newsItemId);

        if (! $item || $item->contentHash() !== $this->sourceHash) {
            return; // deleted or content changed again — a newer job will handle it
        }

        [$title, $summary] = $translator->translateBatch(
            [$item->title, $item->summary ?? ''],
            $this->locale
        );

        $content = $item->content
            ? $translator->translateHtml($item->content, $this->locale)
            : null;

        NewsTranslation::updateOrCreate(
            ['news_item_id' => $this->newsItemId, 'locale' => $this->locale],
            [
                'title'       => $title,
                'summary'     => $item->summary ? $summary : null,
                'content'     => $content,
                'source_hash' => $this->sourceHash,
                'status'      => 'completed',
                'error'       => null,
            ]
        );
    }

    public function failed(?Throwable $exception): void
    {
        NewsTranslation::updateOrCreate(
            ['news_item_id' => $this->newsItemId, 'locale' => $this->locale],
            [
                'source_hash' => $this->sourceHash,
                'status'      => 'failed',
                'error'       => substr((string) $exception?->getMessage(), 0, 1000),
            ]
        );
    }
}
