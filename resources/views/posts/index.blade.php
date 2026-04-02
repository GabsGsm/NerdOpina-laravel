@php
$seo = [
    'title'       => config('blog.name'),
    'description' => config('blog.description'),
];
@endphp

@extends('layouts.app')

@section('content')
<div class="min-h-screen">

    {{-- Hero Section (full-width) --}}
    @if ($featuredPost)
    @php
        $heroDate = $featuredPost->published_at->format('d') . ' de ' . $featuredPost->published_at->translatedFormat('F') . ' de ' . $featuredPost->published_at->format('Y');
    @endphp
    <section class="mb-10">
        <div class="relative overflow-hidden">
            <div class="relative h-112 md:h-152">
                @if ($featuredPost->featured_image)
                <img src="{{ $featuredPost->getFeaturedImageUrl() }}"
                    alt="{{ $featuredPost->featured_image_alt ?? $featuredPost->title }}"
                    class="w-full h-full object-cover"
                    loading="eager">
                @else
                <div class="w-full h-full bg-bg-elevated"></div>
                @endif
                <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/30 to-transparent"></div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 md:p-14">
                <div class="max-w-3xl">
                    @include('partials.category-badge', ['category' => $featuredPost->category])
                    <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold
                               text-white mt-4 mb-4 leading-tight"
                        style="text-shadow: 0 2px 16px rgba(0,0,0,.4)">
                        {{ $featuredPost->title }}
                    </h1>
                    <p class="text-white/75 text-base md:text-lg mb-6 line-clamp-2">
                        {{ $featuredPost->excerpt }}
                    </p>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-white/55 mb-7">
                        <time datetime="{{ $featuredPost->published_at->toIso8601String() }}">
                            {{ $heroDate }}
                        </time>
                    </div>
                    <a href="{{ $featuredPost->getUrl() }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-white rounded-xl
                              font-semibold hover:opacity-90 transition-all duration-150 shadow-lg shadow-accent/30">
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

    {{-- Layout principal: main + sidebar --}}
    <div class="site-container pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Coluna principal (2/3) --}}
            <div class="lg:col-span-2 flex flex-col gap-12">

                @forelse ($sections as $section)
                    @if ($section->posts->isEmpty())
                        @continue
                    @endif

                    <section>
                        {{-- Cabeçalho da seção --}}
                        @if ($section->show_title || $section->show_view_all)
                        <div class="flex items-center justify-between mb-5">
                            @if ($section->show_title)
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-7 rounded-full"
                                     @if($section->accent_color)
                                         style="background-color: {{ $section->accent_color }}"
                                     @else
                                         style="background-color: var(--color-accent)"
                                     @endif
                                ></div>
                                <h2 class="font-heading text-xl md:text-2xl font-bold text-text-high">
                                    {{ $section->title }}
                                </h2>
                            </div>
                            @endif

                            @if ($section->show_view_all && $section->view_all_url)
                            <a href="{{ $section->view_all_url }}"
                               class="text-sm text-accent hover:opacity-75 transition-opacity duration-150 font-medium">
                                Ver todos →
                            </a>
                            @endif
                        </div>
                        @endif

                        {{-- Conteúdo: cards ou lista --}}
                        @if ($section->post_type === 'toplist' || $section->layout === 'card')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                @foreach ($section->posts as $post)
                                    @if ($section->post_type === 'toplist')
                                        @include('partials.toplist-card', ['post' => $post])
                                    @else
                                        @include('partials.article-card', ['post' => $post])
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="bg-bg-surface rounded-xl border border-border-subtle px-5 divide-y divide-border-subtle">
                                @foreach ($section->posts as $post)
                                    @include('partials.news-item', ['post' => $post])
                                @endforeach
                            </div>
                        @endif
                    </section>
                @empty
                    <p class="text-text-low text-sm py-10 text-center">Nenhuma seção configurada.</p>
                @endforelse

            </div>

            {{-- Sidebar (1/3) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    @include('partials.sidebar', [
                        'popularPosts'    => $popularPosts,
                        'featuredTopList' => null,
                    ])
                </div>
            </div>

        </div>

        {{-- Newsletter --}}
        {{-- <div class="mt-16">@include('partials.newsletter')</div> --}}

    </div>
</div>
@endsection
