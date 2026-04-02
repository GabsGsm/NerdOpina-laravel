@props(['post'])

@php
$colorMap = [
    'reviews'    => 'text-cat-reviews',
    'top-listas' => 'text-cat-top-listas',
    'guias'      => 'text-cat-guias',
    'tech'       => 'text-cat-tech',
    'noticias'   => 'text-cat-noticias',
    'anime'      => 'text-cat-anime',
    'kpop'       => 'text-cat-kpop',
    'games'      => 'text-cat-games',
];
$categoryColor = $colorMap[strtolower($post->category->slug)] ?? null;
@endphp

<a href="{{ $post->getUrl() }}"
   class="flex items-center gap-4 py-3 border-b border-border-subtle last:border-0 group">

    @if ($post->featured_image)
    <img src="{{ $post->getFeaturedImageUrl() }}"
         alt="{{ $post->featured_image_alt ?? $post->title }}"
         class="w-18 h-18 rounded-lg object-cover shrink-0"
         loading="lazy">
    @else
    <div class="w-18 h-18 rounded-lg bg-bg-elevated shrink-0 flex items-center justify-center">
        <svg class="w-6 h-6 text-text-low" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
        </svg>
    </div>
    @endif

    <div class="flex-1 min-w-0">
        <h4 class="text-sm font-medium text-text-high group-hover:text-accent
                   transition-colors duration-150 line-clamp-2">
            {{ $post->title }}
        </h4>
        <div class="flex items-center gap-2 mt-1">
            <time class="text-xs text-text-low"
                  datetime="{{ $post->published_at->toIso8601String() }}">
                {{ $post->published_at->diffForHumans() }}
            </time>
            <span class="text-xs text-text-low">·</span>
            @if ($categoryColor)
                <span class="text-xs font-medium {{ $categoryColor }}">{{ $post->category->name }}</span>
            @else
                <span class="text-xs font-medium" style="color: {{ $post->category->color }}">{{ $post->category->name }}</span>
            @endif
        </div>
    </div>
</a>
