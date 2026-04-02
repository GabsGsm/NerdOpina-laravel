@props(['post'])

<a href="{{ $post->getUrl() }}" class="group block h-full">
    <article class="bg-bg-surface rounded-xl overflow-hidden border border-border-subtle
                    hover:border-border-focus transition-all duration-200 hover:shadow-lg h-full flex flex-col">

        {{-- Imagem --}}
        @if ($post->featured_image)
        <div class="aspect-video overflow-hidden">
            <img src="{{ $post->getFeaturedImageUrl() }}"
                 alt="{{ $post->featured_image_alt ?? $post->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy" decoding="async">
        </div>
        @else
        <div class="aspect-video bg-bg-elevated flex items-center justify-center">
            <svg class="w-12 h-12 text-text-low" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        @endif

        {{-- Conteúdo --}}
        <div class="p-5 flex flex-col flex-1 gap-2">
            @include('partials.category-badge', ['category' => $post->category, 'linked' => false])

            <h3 class="font-heading font-bold text-text-high group-hover:text-accent
                       transition-colors duration-150 line-clamp-2 leading-snug mt-1">
                {{ $post->title }}
            </h3>

            <p class="text-sm text-text-medium line-clamp-2 flex-1">
                {{ $post->excerpt }}
            </p>

            {{-- Meta --}}
            @php
                $readingMinutes = max(1, (int) ceil(str_word_count(strip_tags($post->content)) / 200));
            @endphp
            <div class="flex items-center gap-3 text-xs text-text-low mt-auto pt-2 border-t border-border-subtle">
                <time datetime="{{ $post->published_at->toIso8601String() }}">
                    {{ $post->published_at->translatedFormat('d M Y') }}
                </time>
            </div>
        </div>
    </article>
</a>
