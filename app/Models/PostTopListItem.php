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

    public function getImageUrl(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        return route('img', ['path' => $this->image_url]);
    }
}
