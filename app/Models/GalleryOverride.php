<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryOverride extends Model
{
    protected $fillable = ['path', 'album_key', 'event_date', 'caption', 'is_manual', 'is_hidden'];

    protected $casts = [
        'event_date' => 'date',
        'is_manual'  => 'boolean',
        'is_hidden'  => 'boolean',
    ];
}
