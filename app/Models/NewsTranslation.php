<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NewsTranslation extends Model
{
    protected $table = 'news_item_translations';

    protected $fillable = [
        'news_item_id', 'locale', 'title', 'summary', 'content',
        'source_hash', 'status', 'error',
    ];

    /**
     * Completed translations for a set of news items in one locale,
     * keyed by news_item_id.
     */
    public static function forItems(Collection $items, string $locale): Collection
    {
        return static::where('locale', $locale)
            ->where('status', 'completed')
            ->whereIn('news_item_id', $items->pluck('id'))
            ->get()
            ->keyBy('news_item_id');
    }
}
