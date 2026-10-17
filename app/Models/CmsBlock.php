<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsBlock extends Model
{
    protected $fillable = ['page_key', 'block_key', 'value'];
}
