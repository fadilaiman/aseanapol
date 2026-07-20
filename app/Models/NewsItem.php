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
        'is_upcoming_event',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_upcoming_event' => 'boolean',
    ];

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
