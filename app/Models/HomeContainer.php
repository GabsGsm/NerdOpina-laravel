<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomeContainer extends Model
{
    protected $fillable = [
        'type',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(HomeSection::class, 'container_id');
    }

    public function sidebarWidgets(): HasMany
    {
        return $this->hasMany(HomeSidebarWidget::class, 'container_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
