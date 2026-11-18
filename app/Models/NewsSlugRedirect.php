<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSlugRedirect extends Model
{
    protected $fillable = ['old_slug', 'news_item_id'];
}
