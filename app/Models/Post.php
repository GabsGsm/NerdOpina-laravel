<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    protected $fillable = [
        'category_id', 'type', 'title', 'slug', 'excerpt', 'content',
        'featured_image', 'featured_image_alt',
        'meta_title', 'meta_description', 'canonical_url', 'robots',
        'og_title', 'og_image', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // --- Relationships ---

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function topListItems()
    {
        return $this->hasMany(PostTopListItem::class)->orderBy('rank');
    }

    // --- Scopes ---

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now())
                     ->orderByDesc('published_at');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // --- Type helpers ---

    public function isTopList(): bool
    {
        return $this->type === 'toplist';
    }

    public function isArticle(): bool
    {
        return $this->type === 'article';
    }

    public function getFormattedDate(): string
    {
        return $this->published_at->format('d') . ' de ' .
            $this->published_at->translatedFormat('F') . ' de ' .
            $this->published_at->format('Y');
    }

    // Retorna os itens da toplist prontos para o card
    // Se no futuro quiser exibir no content, chama esse mesmo método
    public function getTopListItems(): \Illuminate\Database\Eloquent\Collection
    {
        if (! $this->isTopList()) {
            return collect();
        }

        // Eager load seguro — se já carregado não faz nova query
        return $this->relationLoaded('topListItems')
            ? $this->topListItems
            : $this->topListItems()->get();
    }

    // --- SEO helpers ---

    public function getSeoTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getSeoDescription(): string
    {
        return $this->meta_description ?: $this->excerpt;
    }

    public function getOgImage(): ?string
    {
        return $this->og_image ?: $this->featured_image;
    }

    public function getUrl(): string
    {
        return route('posts.show', $this->slug);
    }

    // --- Schema.org ---

    public function getSchemaMarkup(): array
    {
        $schema = [
            '@context'      => 'https://schema.org',
            '@type'         => $this->isTopList() ? 'ItemList' : 'Article',
            'headline'      => $this->title,
            'description'   => $this->getSeoDescription(),
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified'  => $this->updated_at->toIso8601String(),
            'url'           => $this->getUrl(),
            'author' => [
                '@type' => 'Organization',
                'name'  => config('blog.name'),
                'url'   => config('app.url'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name'  => config('blog.name'),
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => asset(config('blog.logo')),
                ],
            ],
        ];

        // Schema específico para TopList — melhora rich results no Google
        if ($this->isTopList() && $this->relationLoaded('topListItems')) {
            $schema['numberOfItems'] = $this->topListItems->count();
            $schema['itemListElement'] = $this->topListItems->map(fn($item) => [
                '@type'    => 'ListItem',
                'position' => $item->rank,
                'name'     => $item->title,
                'url'      => $item->external_url ?? $this->getUrl(),
            ])->toArray();
        }

        if ($this->getOgImage()) {
            $schema['image'] = asset($this->getOgImage());
        }

        return $schema;
    }
}