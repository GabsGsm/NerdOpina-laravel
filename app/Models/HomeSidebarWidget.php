<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSidebarWidget extends Model
{
    protected $fillable = [
        'container_id',
        'type',
        'title',
        'limit',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'limit' => 'integer',
            'position' => 'integer',
        ];
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(HomeContainer::class, 'container_id');
    }
}
