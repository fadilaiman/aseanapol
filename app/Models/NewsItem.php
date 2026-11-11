<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsItem extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'title', 'summary', 'content', 'author',
        'published_at', 'slug', 'views_count', 'thumbnail',
        'is_upcoming_event', 'sponsors',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_upcoming_event' => 'boolean',
        'sponsors' => 'array',
    ];

    private const GALLERY_RE = '#\s*<div class="news-gallery[^"]*">(.*?)</div>#is';

    /** Image URLs inside the article's trailing photo gallery, in order. */
    public function galleryImages(): array
    {
        if (! preg_match(self::GALLERY_RE, (string) $this->content, $m)) {
            return [];
        }
        preg_match_all('#<img[^>]+src=(?:"([^"]+)"|\'([^\']+)\')#i', $m[1], $im, PREG_SET_ORDER);

        return array_map(fn ($r) => html_entity_decode($r[1] !== '' ? $r[1] : ($r[2] ?? '')), $im);
    }

    /** Content with the photo gallery removed (it is edited as photo tiles instead). */
    public function contentWithoutGallery(): string
    {
        return trim(preg_replace(self::GALLERY_RE, '', (string) $this->content, 1));
    }

    /** Sponsor rows safe to render: [['name','logo','url'?]]; empty when none. */
    public function sponsorItems(): array
    {
        $items = is_array($this->sponsors) ? ($this->sponsors['items'] ?? []) : [];

        return array_values(array_filter($items, fn ($i) => is_array($i) && ! empty($i['logo'])));
    }

    public function sponsorLabel(): ?string
    {
        $l = is_array($this->sponsors) ? ($this->sponsors['label'] ?? null) : null;

        return is_string($l) && trim($l) !== '' ? trim($l) : null;
    }

    /**
     * Hash of the translatable source fields. A stored translation is only
     * valid while its source_hash matches this.
     */
    public function contentHash(): string
    {
        return hash('sha256', ($this->title ?? '') . '|' . ($this->summary ?? '') . '|' . ($this->content ?? ''));
    }

    /**
     * Swap in the cached translation for $locale when a fresh completed one
     * exists; otherwise the item silently keeps its English fields.
     */
    public function applyTranslation(string $locale): void
    {
        if ($locale === 'en') {
            return;
        }

        $translation = NewsTranslation::where('news_item_id', $this->id)
            ->where('locale', $locale)
            ->where('status', 'completed')
            ->first();

        $this->applyTranslationRow($translation);
    }

    public function applyTranslationRow(?NewsTranslation $translation): void
    {
        if (! $translation || $translation->source_hash !== $this->contentHash()) {
            return;
        }

        $this->title   = $translation->title ?: $this->title;
        $this->summary = $translation->summary ?: $this->summary;
        $this->content = $translation->content ?: $this->content;
    }
}
