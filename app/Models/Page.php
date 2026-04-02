<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
    ];

    public function getUrl(): string
    {
        return route('pages.show', $this->slug);
    }

    public function getSeoTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getSeoDescription(): string
    {
        return $this->meta_description ?? '';
    }
}
