<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DigitalLibraryItem extends Model
{
    protected $fillable = [
        'collection_id', 'title', 'type', 'file_path', 'external_url', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(DigitalLibraryCollection::class, 'collection_id');
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->external_url) {
            return $this->external_url;
        }

        if ($this->file_path) {
            return Storage::url($this->file_path);
        }

        return null;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
