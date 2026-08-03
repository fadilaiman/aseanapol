<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['album_key', 'path', 'event_date'];

    protected $casts = [
        'event_date' => 'date',
    ];
}
