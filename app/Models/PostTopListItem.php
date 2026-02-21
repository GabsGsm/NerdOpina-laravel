<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTopListItem extends Model
{
    protected $fillable = [
        'post_id', 'rank', 'title', 'image_url', 'external_url', 'description',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}