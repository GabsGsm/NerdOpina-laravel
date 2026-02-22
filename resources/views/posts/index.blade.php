@php
$seo = [
    'title'       => config('blog.name'),
    'description' => config('blog.description'),
];
@endphp

@extends('layouts.app')

@section('content')
@php
    $hero = $posts->first();
@endphp
<div class="min-h-screen">

    {{-- Hero Section --}}
    @if ($hero)
    @php
        $heroDate = $hero->published_at->format('d') . ' de ' . $hero->published_at->translatedFormat('F') . ' de ' . $hero->published_at->format('Y');
    @endphp
    <section class="mb-16">
        <div class="relative overflow-hidden">

            {{-- Imagem --}}
            <div class="relative h-140">
                @if ($hero->featured_image)
                <img src="{{ asset($hero->featured_image) }}"
                    alt="{{ $hero->featured_image_alt ?? $hero->title }}"
                    class="w-full h-full object-cover"
                    loading="eager">
                @else
                <div class="w-full h-full bg-bg-elevated"></div>
                @endif

                {{-- Camada escura sobre a imagem --}}
                <div class="absolute inset-0 bg-black/50"></div>
            </div>

            {{-- Conteúdo sobre a imagem --}}
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                <div class="max-w-3xl">

                    @include('partials.category-badge', ['category' => $hero->category])

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mt-4 mb-4 leading-tight drop-shadow-lg"
                        style="font-family: var(--font-heading)">
                        {{ $hero->title }}
                    </h1>

                    <p class="text-white/80 text-lg mb-6 line-clamp-2 drop-shadow">
                        {{ $hero->excerpt }}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-white/60 mb-6">
                        <time datetime="{{ $hero->published_at->toIso8601String() }}">
                            {{ $heroDate }}
                        </time>
                    </div>

                    <a href="{{ $hero->getUrl() }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-white rounded-lg font-medium hover:opacity-90 transition-all duration-150 shadow-lg">
                        Ler artigo
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>

                </div>
            </div>
        </div>
    </section>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

        {{-- Últimos Artigos --}}
        @if ($posts->count() > 1)
        <section class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-8 bg-cat-reviews rounded-full"></div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text-high"
                        style="font-family: var(--font-heading)">
                        Últimos Artigos
                    </h2>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts->skip(1)->take(3) as $post)
                    @include('partials.article-card', ['post' => $post])
                @endforeach
            </div>
        </section>
        @endif

        {{-- Top Listas --}}
        @if ($topLists->isNotEmpty())
        <section class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-8 bg-cat-top-listas rounded-full"></div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text-high"
                        style="font-family: var(--font-heading)">
                        Top Listas
                    </h2>
                </div>
                <a href="{{ route('categories.show', 'top-listas') }}"
                   class="text-sm text-accent hover:opacity-80 transition-colors duration-150">
                    Ver todas →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($topLists->take(3) as $post)
                    @include('partials.toplist-card', ['post' => $post])
                @endforeach
            </div>
        </section>
        @endif

        {{-- Grid: Notícias + Sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">

            {{-- Coluna principal: Notícias --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 bg-cat-noticias rounded-full"></div>
                        <h2 class="text-2xl md:text-3xl font-bold text-text-high"
                            style="font-family: var(--font-heading)">
                            Últimas Notícias
                        </h2>
                    </div>
                </div>
                <div class="bg-bg-surface rounded-lg border border-border-subtle px-5 divide-y divide-border-subtle">
                    @foreach ($latestNews as $news)
                        @include('partials.news-item', ['post' => $news])
                    @endforeach
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    @include('partials.sidebar', [
                        'popularPosts'    => $popularPosts,
                        'featuredTopList' => $featuredTopList,
                    ])
                </div>
            </div>

        </div>

        {{-- Newsletter --}}
        @include('partials.newsletter')

        {{-- Paginação --}}
        @if ($posts->hasPages())
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
        @endif

    </div>
</div>
@endsection