@php
    $widgetPosts = \App\Models\Post::published()
        ->with('category')
        ->limit($widget->limit ?? 5)
        ->get();
@endphp

@if ($widgetPosts->isNotEmpty())
<div class="bg-bg-surface rounded-xl p-5 border border-border-subtle">
    <h3 class="font-heading font-bold text-text-high mb-4 text-sm uppercase tracking-wide">
        {{ $widget->title ?? 'Posts Populares' }}
    </h3>
    <div class="space-y-4">
        @foreach ($widgetPosts as $index => $post)
        <a href="{{ $post->getUrl() }}" class="flex items-start gap-3 group">
            <span class="font-heading text-xl font-black text-accent shrink-0 w-6 leading-none">
                {{ $index + 1 }}
            </span>
            @if ($post->featured_image)
            <img src="{{ $post->getFeaturedImageUrl() }}"
                 alt="{{ $post->featured_image_alt ?? $post->title }}"
                 class="w-14 h-14 rounded-lg object-cover shrink-0"
                 loading="lazy">
            @endif
            <h4 class="text-sm font-medium text-text-high group-hover:text-accent
                       transition-colors duration-150 line-clamp-3 flex-1 leading-snug">
                {{ $post->title }}
            </h4>
        </a>
        @endforeach
    </div>
</div>
@endif
