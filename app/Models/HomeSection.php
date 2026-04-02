<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'title',
        'show_title',
        'show_view_all',
        'view_all_url',
        'post_type',
        'layout',
        'category_ids',
        'order_by',
        'limit',
        'is_active',
        'position',
        'accent_color',
    ];

    protected function casts(): array
    {
        return [
            'show_title' => 'boolean',
            'show_view_all' => 'boolean',
            'is_active' => 'boolean',
            'category_ids' => 'array',
            'limit' => 'integer',
            'position' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Monta a query de posts para esta seção com base nas regras configuradas.
     */
    public function buildPostQuery(): Builder
    {
        $query = Post::published()
            ->with('category')
            ->ofType($this->post_type);

        if ($this->post_type === 'toplist') {
            $query->with('topListItems');
        }

        if (! empty($this->category_ids)) {
            $query->whereIn('category_id', $this->category_ids);
        }

        // Remove a ordenação do scopePublished e aplica a da seção
        $query->reorder();

        if ($this->order_by === 'oldest') {
            $query->orderBy('published_at');
        } else {
            $query->orderByDesc('published_at');
        }

        return $query->limit($this->limit);
    }
}
