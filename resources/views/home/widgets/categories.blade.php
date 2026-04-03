@php
    $widgetCategories = \App\Models\Category::whereNull('parent_id')
        ->withCount(['posts' => fn ($q) => $q->published()])
        ->get();
@endphp

<div class="bg-bg-surface rounded-xl p-5 border border-border-subtle">
    <h3 class="font-heading font-bold text-text-high mb-4 text-sm uppercase tracking-wide">
        {{ $widget->title ?? 'Categorias' }}
    </h3>
    <div class="divide-y divide-border-subtle">
        @foreach ($widgetCategories as $cat)
        <a href="{{ $cat->getUrl() }}"
           class="flex items-center justify-between py-2.5 text-sm text-text-medium
                  hover:text-accent transition-colors duration-150 group">
            <span>{{ $cat->name }}</span>
            <span class="text-xs text-text-low group-hover:text-accent bg-bg-elevated
                         px-2 py-0.5 rounded-full">
                {{ $cat->posts_count }}
            </span>
        </a>
        @endforeach
    </div>
</div>
