<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'title', 'edition', 'issue_date', 'topics', 'file_path', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'topics' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
