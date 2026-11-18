<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DigitalLibraryCollection extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'is_visible', 'sort_order'];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DigitalLibraryItem::class, 'collection_id');
    }

    public function publishedItems(): HasMany
    {
        return $this->hasMany(DigitalLibraryItem::class, 'collection_id')
            ->where('is_published', true)
            ->orderBy('sort_order');
    }
}
