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

    /**
     * What the admin "Content" box should show. Articles typed as plain text are stored as simple
     * <p>/<br>/<ul><li> HTML; hand that back as plain text so editors never see tags.
     * Richer markup (links, bold, images, tables...) is returned unchanged.
     */
    public function contentForEditing(): string
    {
        $html = $this->contentWithoutGallery();

        return self::simpleHtmlToPlain($html) ?? $html;
    }

    /** Inverse of NewsItemController::autoParagraph(); null when the HTML is anything richer than bare p/br/ul/li. */
    public static function simpleHtmlToPlain(string $html): ?string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }
        $rest = preg_replace('#<p>.*?</p>|<ul>.*?</ul>#is', '', $html);
        if (trim((string) $rest) !== '') {
            return null; // stray text/markup outside simple blocks
        }
        preg_match_all('#<p>(.*?)</p>|<ul>(.*?)</ul>#is', $html, $blocks, PREG_SET_ORDER);

        $decode = fn (string $s) => html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $out = [];
        foreach ($blocks as $b) {
            if (($b[1] ?? '') !== '' || str_starts_with($b[0], '<p>')) {
                $inner = $b[1] ?? '';
                $inner = preg_replace('#<br\s*/?>#i', "\n", $inner);
                if (preg_match('#<[^>]+>#', $inner)) {
                    return null; // inline markup (links, bold...) cannot be shown as plain text without loss
                }
                $text = trim($decode($inner));
                if ($text !== '') {
                    $out[] = $text;
                }
            } else {
                $items = [];
                if (! preg_match_all('#<li>(.*?)</li>#is', $b[2], $li)) {
                    return null;
                }
                foreach ($li[1] as $item) {
                    if (preg_match('#<[^>]+>#', $item)) {
                        return null;
                    }
                    $items[] = '* ' . trim($decode($item));
                }
                if (trim(preg_replace('#<li>.*?</li>#is', '', $b[2])) !== '') {
                    return null;
                }
                $out[] = implode("\n", $items);
            }
        }

        return implode("\n\n", $out);
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
