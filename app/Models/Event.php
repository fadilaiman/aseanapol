<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public const KINDS = [
        'calendar'   => 'Calendar event',
        'conference' => 'Conference',
        'training'   => 'Training programme',
    ];

    public const TYPES = ['Conference', 'Meeting', 'Training', 'Activity'];

    public const STATUSES = [
        'upcoming'  => 'Forthcoming',
        'past'      => 'Past',
        'suspended' => 'Suspended (not held)',
    ];

    protected $fillable = [
        'kind', 'title', 'start_date', 'end_date', 'city', 'country', 'type', 'description', 'link', 'link_label', 'image',
        'conf_number', 'conf_year', 'dates_label', 'flag', 'theme', 'status',
        'abbr', 'icon', 'category', 'frequency', 'latest', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date'   => 'date',
            'end_date'     => 'date',
            'is_published' => 'boolean',
            'conf_year'    => 'integer',
            'sort_order'   => 'integer',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeKind($query, string $kind)
    {
        return $query->where('kind', $kind);
    }

    public function getLocationAttribute(): string
    {
        return trim(implode(', ', array_filter([$this->city, $this->country])));
    }

    /** Public URL of the uploaded image ('' when none). */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return '';
        }

        return preg_match('#^https?://#', $this->image) ? $this->image : asset('storage/' . ltrim($this->image, '/'));
    }
}
