@extends('layouts.app')

@php
$seo = [
    'title'       => $post->getSeoTitle(),
    'description' => $post->getSeoDescription(),
    'robots'      => $post->robots,
    'canonical'   => $post->canonical_url ?: $post->getUrl(),
    'og_type'     => 'article',
    'og_image'    => $post->getOgImage() ? asset($post->getOgImage()) : null,
    'schema'      => $post->getSchemaMarkup(),
];
$postDate = $post->published_at->format('d') . ' de ' . $post->published_at->translatedFormat('F') . ' de ' . $post->published_at->format('Y');
@endphp

@push('header-bottom')
<div class="h-0.5 bg-border-subtle w-full"
     x-data="readProgress()"
     x-init="init()"
     @scroll.window="update()">
    <div class="h-full bg-accent transition-all duration-100"
         :style="'width:' + progress + '%'"></div>
</div>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-medium mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-text-high transition-colors duration-150">Home</a>
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        @if ($post->category->parent)
        <a href="{{ $post->category->parent->getUrl() }}" class="hover:text-text-high transition-colors duration-150">
            {{ $post->category->parent->name }}
        </a>
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        @endif
        <a href="{{ $post->category->getUrl() }}" class="hover:text-text-high transition-colors duration-150">
            {{ $post->category->name }}
        </a>
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-text-high truncate max-w-64">{{ $post->title }}</span>
    </nav>

    {{-- Hero Image --}}
    @if ($post->featured_image)
    <div class="relative rounded-2xl overflow-hidden mb-8 h-80 md:h-120">
        <img src="{{ $post->getFeaturedImageUrl() }}"
             alt="{{ $post->featured_image_alt ?? $post->title }}"
             class="w-full h-full object-cover"
             loading="eager">
        <div class="absolute inset-0 bg-linear-to-t from-bg-base via-bg-base/30 to-transparent"></div>

        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
            @include('partials.category-badge', ['category' => $post->category])
            <h1 class="font-heading text-2xl md:text-4xl lg:text-5xl font-extrabold text-white
                       mt-3 mb-0 leading-tight max-w-4xl"
                style="text-shadow: 0 2px 12px rgba(0,0,0,.5)"
                itemprop="headline">
                {{ $post->title }}
            </h1>
        </div>
    </div>
    @else
    <div class="mb-8">
        @include('partials.category-badge', ['category' => $post->category])
        <h1 class="font-heading text-2xl md:text-4xl lg:text-5xl font-extrabold text-text-high
                   mt-3 mb-4 leading-tight"
            itemprop="headline">
            {{ $post->title }}
        </h1>
    </div>
    @endif

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Artigo principal --}}
        <article class="lg:col-span-2" itemscope itemtype="https://schema.org/Article">

            {{-- Meta: autor + data + compartilhar --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-6 border-b border-border-subtle">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center
                                text-white font-bold text-base shrink-0">
                        N
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text-high">NerdOpina</p>
                        <div class="flex items-center gap-1.5 text-xs text-text-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <time datetime="{{ $post->published_at->toIso8601String() }}" itemprop="datePublished">
                                {{ $postDate }}
                            </time>
                        </div>
                    </div>
                </div>

                {{-- Compartilhar --}}
                <div class="flex items-center gap-1"
                     x-data="shareButtons('{{ addslashes($post->title) }}', '{{ $post->getUrl() }}')">
                    <span class="text-xs text-text-medium mr-1">Compartilhar:</span>
                    <a :href="facebookUrl" target="_blank" rel="noopener noreferrer"
                       class="p-2 text-text-medium hover:text-accent hover:bg-bg-elevated rounded-lg
                              transition-all duration-150" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                    <a :href="twitterUrl" target="_blank" rel="noopener noreferrer"
                       class="p-2 text-text-medium hover:text-accent hover:bg-bg-elevated rounded-lg
                              transition-all duration-150" aria-label="Twitter">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                        </svg>
                    </a>
                    <button @click="copyLink()"
                            class="p-2 text-text-medium hover:text-accent hover:bg-bg-elevated rounded-lg
                                   transition-all duration-150" aria-label="Copiar link">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- TopList completa --}}
            @if ($post->isTopList())
            <section class="mb-8" aria-label="Lista completa">
                <ol class="flex flex-col gap-3">
                    @foreach ($post->getTopListItems() as $item)
                    <li class="flex items-center gap-4 bg-bg-surface rounded-xl p-4 border border-border-subtle
                               hover:border-border-focus transition-colors duration-150">
                        <span class="font-heading text-2xl font-black text-accent w-8 text-center shrink-0 leading-none">
                            {{ $item->rank }}
                        </span>
                        @if ($item->getImageUrl())
                        <img src="{{ $item->getImageUrl() }}"
                             alt="{{ $item->title }}"
                             class="w-14 h-14 rounded-lg object-cover shrink-0"
                             loading="lazy">
                        @endif
                        <div class="flex flex-col gap-1 flex-1 min-w-0">
                            @if ($item->external_url)
                            <a href="{{ $item->external_url }}" target="_blank" rel="noopener noreferrer"
                               class="font-semibold hover:text-accent transition-colors duration-150 truncate">
                                {{ $item->title }}
                            </a>
                            @else
                            <span class="font-semibold truncate">{{ $item->title }}</span>
                            @endif
                            @if ($item->description)
                            <p class="text-sm text-text-medium">{{ $item->description }}</p>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ol>
            </section>
            @endif

            {{-- Conteúdo do artigo --}}
            <div class="prose prose-base lg:prose-lg max-w-none
                        prose-headings:font-heading prose-headings:font-bold prose-headings:text-text-high
                        prose-p:text-text-medium prose-p:leading-relaxed
                        prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                        prose-blockquote:border-l-4 prose-blockquote:border-accent
                        prose-blockquote:bg-bg-elevated prose-blockquote:rounded-r-xl
                        prose-blockquote:not-italic prose-blockquote:text-text-high prose-blockquote:px-5
                        prose-strong:text-text-high
                        prose-code:text-accent prose-code:bg-bg-elevated prose-code:rounded-md prose-code:px-1.5 prose-code:py-0.5
                        prose-pre:bg-bg-elevated prose-pre:border prose-pre:border-border-subtle prose-pre:rounded-xl
                        prose-img:rounded-xl prose-img:border prose-img:border-border-subtle
                        prose-hr:border-border-subtle
                        prose-li:text-text-medium"
                 itemprop="articleBody">
                {!! $post->content !!}
            </div>

            {{-- Posts relacionados --}}
            @if ($related->isNotEmpty())
            <section class="mt-16">
                <h3 class="font-heading text-2xl font-bold text-text-high mb-6">
                    Posts Relacionados
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                    @foreach ($related as $item)
                        @if ($item->isTopList())
                            @include('partials.toplist-card', ['post' => $item])
                        @else
                            @include('partials.article-card', ['post' => $item])
                        @endif
                    @endforeach
                </div>
            </section>
            @endif

        </article>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                @include('partials.sidebar', [
                    'popularPosts'    => $related->isNotEmpty() ? $related : collect(),
                    'featuredTopList' => null,
                ])
            </div>
        </div>

    </div>
</div>

<script>
function readProgress() {
    return {
        progress: 0,
        init() { this.update(); },
        update() {
            const winH = window.innerHeight;
            const docH = document.documentElement.scrollHeight;
            const scrollTop = window.scrollY;
            this.progress = Math.min((scrollTop / (docH - winH)) * 100, 100);
        }
    }
}

function shareButtons(title, url) {
    return {
        facebookUrl: 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url),
        twitterUrl: 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(title) + '&url=' + encodeURIComponent(url),
        async copyLink() {
            try {
                await navigator.clipboard.writeText(url);
                alert('Link copiado!');
            } catch(e) {
                console.error(e);
            }
        }
    }
}
</script>
@endsection
