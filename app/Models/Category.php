<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'color',
        'meta_title', 'meta_description',
    ];

    // --- Relationships ---

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // --- Helpers ---

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    // Retorna todas as categorias da árvore (pai + filhos)
    // útil para queries que precisam incluir subcategorias
    public function allChildrenIds(): array
    {
        return $this->children()->pluck('id')->prepend($this->id)->toArray();
    }

    // Badge inline para Tailwind com cor dinâmica do admin
    public function badgeStyle(): string
    {
        return "background-color: {$this->color}";
    }

    public function getUrl(): string
    {
        return route('categories.show', $this->slug);
    }

    public function getSeoTitle(): string
    {
        return $this->meta_title ?: $this->name;
    }

    public function getSeoDescription(): string
    {
        return $this->meta_description ?: $this->description ?? '';
    }
}