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
}
