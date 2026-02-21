@props(['popularPosts' => collect(), 'featuredTopList' => null])

<aside class="space-y-6">

    {{-- Posts Populares --}}
    @if ($popularPosts->isNotEmpty())
    <div class="bg-bg-surface rounded-lg p-5 border border-border-subtle">
        <h3 class="font-bold text-text-high mb-4" style="font-family: var(--font-heading)">
            Posts Populares
        </h3>
        <div class="space-y-4">
            @foreach ($popularPosts as $index => $post)
            <a href="{{ $post->getUrl() }}" class="flex items-start gap-3 group">
                <span class="text-xl font-bold text-accent flex-shrink-0 w-6"
                      style="font-family: var(--font-heading)">
                    {{ $index + 1 }}
                </span>
                @if ($post->featured_image)
                <img src="{{ asset($post->featured_image) }}"
                     alt="{{ $post->featured_image_alt ?? $post->title }}"
                     class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
                     loading="lazy">
                @endif
                <h4 class="text-sm font-medium text-text-high group-hover:text-accent
                           transition-colors duration-150 line-clamp-2 flex-1">
                    {{ $post->title }}
                </h4>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- TopList em destaque --}}
    @if ($featuredTopList)
        @include('partials.toplist-card', ['post' => $featuredTopList, 'compact' => true])
    @endif

    {{-- Categorias --}}
    @php
        $sidebarCategories = \App\Models\Category::whereNull('parent_id')
            ->withCount(['posts' => fn($q) => $q->published()])
            ->get();
    @endphp

    <div class="bg-bg-surface rounded-lg p-5 border border-border-subtle">
        <h3 class="font-bold text-text-high mb-4" style="font-family: var(--font-heading)">
            Categorias
        </h3>
        <div class="space-y-1">
            @foreach ($sidebarCategories as $cat)
            <a href="{{ $cat->getUrl() }}"
               class="flex items-center justify-between py-2 text-sm text-text-medium
                      hover:text-accent transition-colors duration-150 group">
                <span>{{ $cat->name }}</span>
                <span class="text-xs text-text-low group-hover:text-accent">
                    {{ $cat->posts_count }}
                </span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Newsletter --}}
    @include('partials.newsletter', ['compact' => true])

</aside>