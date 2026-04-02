@extends('layouts.app')

@php
$seo = [
    'title'       => $query ? "Busca: {$query}" : 'Buscar',
    'description' => $query ? "Resultados para: {$query}" : 'Busque artigos, reviews, guias e top listas.',
    'robots'      => 'noindex,follow',
];
@endphp

@section('content')
<div class="site-container py-8">

    {{-- Header busca --}}
    <div class="max-w-3xl mx-auto text-center mb-12">
        <h1 class="font-heading text-3xl md:text-4xl font-bold text-text-high mb-6">
            Buscar
        </h1>

        <form action="{{ route('search') }}" method="GET">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-medium"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       name="q"
                       value="{{ $query }}"
                       placeholder="Digite o que você procura..."
                       autofocus
                       class="w-full pl-12 pr-4 py-4 bg-bg-surface border border-border-subtle rounded-xl
                              text-lg text-text-high placeholder:text-text-low
                              focus:outline-none focus:border-border-focus transition-colors duration-150">
            </div>
        </form>

        @if ($query)
        <p class="text-text-medium mt-4">
            Resultados para: <span class="font-medium text-text-high">"{{ $query }}"</span>
        </p>
        @endif
    </div>

    {{-- Filtro por categoria --}}
    <div class="flex flex-wrap justify-center gap-3 mb-8">
        <a href="{{ route('search', ['q' => $query]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150
                  {{ !request('category') ? 'bg-accent text-white' : 'bg-bg-surface text-text-medium hover:text-text-high hover:bg-bg-elevated border border-border-subtle' }}">
            Todos
        </a>
        @foreach ($categories as $cat)
        <a href="{{ route('search', ['q' => $query, 'category' => $cat->slug]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request('category') === $cat->slug ? 'bg-accent text-white' : 'bg-bg-surface text-text-medium hover:text-text-high hover:bg-bg-elevated border border-border-subtle' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    {{-- Resultados --}}
    @if ($query)

        <div class="max-w-6xl mx-auto">

            {{-- Contagem --}}
            <div class="mb-4 text-sm text-text-medium">
                {{ $posts->total() }} {{ $posts->total() === 1 ? 'resultado encontrado' : 'resultados encontrados' }}
            </div>

            @if ($posts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach ($posts as $post)
                    @if ($post->isTopList())
                        @include('partials.toplist-card', ['post' => $post])
                    @else
                        @include('partials.article-card', ['post' => $post])
                    @endif
                @endforeach
            </div>

            {{-- Paginação --}}
            @if ($posts->hasPages())
            <div class="flex items-center justify-center">
                {{ $posts->appends(['q' => $query])->links() }}
            </div>
            @endif

            @else

            {{-- Sem resultados --}}
            <div class="text-center py-12">
                <p class="text-text-medium text-lg mb-2">
                    Nenhum resultado encontrado para "{{ $query }}".
                </p>
                <p class="text-text-low text-sm">
                    Tente palavras-chave diferentes ou explore as categorias abaixo.
                </p>
            </div>

            @endif
        </div>

    @else

    {{-- Estado vazio --}}
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-bg-elevated rounded-full mb-6">
            <svg class="w-10 h-10 text-text-low" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h2 class="font-heading text-2xl font-bold text-text-high mb-4">
            Comece sua busca
        </h2>
        <p class="text-text-medium mb-8 max-w-md mx-auto">
            Digite algo no campo acima para encontrar artigos, reviews, guias e muito mais.
        </p>

        <div class="max-w-2xl mx-auto">
            <p class="text-sm text-text-low mb-4">Ou explore por categoria:</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($categories->take(4) as $cat)
                <a href="{{ $cat->getUrl() }}"
                   class="p-4 bg-bg-surface border border-border-subtle rounded-lg
                          hover:border-border-focus transition-all duration-150 group">
                    <h3 class="font-medium text-text-high group-hover:text-accent transition-colors">
                        {{ $cat->name }}
                    </h3>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    @endif

</div>
@endsection