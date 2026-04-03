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

    {{-- Containers de layout --}}
    @forelse ($containers as $container)
        @if ($container->type === 'two_column')
            @include('home.container-two-column', compact('container'))
        @else
            @include('home.container-box', compact('container'))
        @endif
    @empty
        <div class="site-container pb-16">
            <p class="text-text-low text-sm py-10 text-center">Nenhum container configurado.</p>
        </div>
    @endforelse
</div>
@endsection
