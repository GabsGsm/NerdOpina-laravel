@props(['popularPosts' => collect(), 'featuredTopList' => null])

<aside class="space-y-5">

    {{-- Posts Populares --}}
    @if ($popularPosts->isNotEmpty())
    <div class="bg-bg-surface rounded-xl p-5 border border-border-subtle">
        <h3 class="font-heading font-bold text-text-high mb-4 text-sm uppercase tracking-wide">
            Posts Populares
        </h3>
        <div class="space-y-4">
            @foreach ($popularPosts as $index => $post)
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

    <div class="bg-bg-surface rounded-xl p-5 border border-border-subtle">
        <h3 class="font-heading font-bold text-text-high mb-4 text-sm uppercase tracking-wide">
            Categorias
        </h3>
        <div class="divide-y divide-border-subtle">
            @foreach ($sidebarCategories as $cat)
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

    {{-- Newsletter --}}
    {{-- @include('partials.newsletter', ['compact' => true]) --}}

</aside>
