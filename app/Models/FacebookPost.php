<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Ledger of Facebook posts already ingested (or deliberately skipped) by
 * news:sync-facebook, keyed by the numeric Facebook post id. Guarantees a
 * post is never published twice even if the gateway re-offers it.
 */
class FacebookPost extends Model
{
    protected $fillable = [
        'fb_post_id', 'permalink', 'status', 'news_item_id', 'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
