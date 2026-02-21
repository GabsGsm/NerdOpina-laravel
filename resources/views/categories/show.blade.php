@extends('layouts.app')

@php
$seo = [
    'title'       => $category->getSeoTitle(),
    'description' => $category->getSeoDescription(),
    'canonical'   => $category->getUrl(),
];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header da categoria --}}
    <header class="mb-10">

        {{-- Breadcrumb --}}
        @if ($category->parent)
        <nav class="flex items-center gap-2 text-sm text-text-medium mb-4">
            <a href="{{ route('home') }}" class="hover:text-text-high transition-colors">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ $category->parent->getUrl() }}" class="hover:text-text-high transition-colors">
                {{ $category->parent->name }}
            </a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-text-high">{{ $category->name }}</span>
        </nav>
        @endif

        {{-- Nome + cor --}}
        <div class="flex items-center gap-3 mb-3">
            <div class="w-1.5 h-10 rounded-full" style="background-color: {{ $category->color }}"></div>
            <h1 class="text-3xl md:text-4xl font-bold text-text-high"
                style="font-family: var(--font-heading)">
                {{ $category->name }}
            </h1>
        </div>

        @if ($category->description)
        <p class="text-text-medium mt-2 max-w-2xl">{{ $category->description }}</p>
        @endif

        {{-- Subcategorias filhas --}}
        @if ($category->children->isNotEmpty())
        <div class="flex flex-wrap gap-2 mt-5">
            @foreach ($category->children as $child)
                @include('partials.category-badge', ['category' => $child])
            @endforeach
        </div>
        @endif

    </header>

    {{-- TopLists da categoria --}}
    @if ($topLists->isNotEmpty())
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-8 bg-cat-top-listas rounded-full"></div>
            <h2 class="text-2xl font-bold text-text-high" style="font-family: var(--font-heading)">
                Top Listas
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($topLists as $post)
                @include('partials.toplist-card', ['post' => $post])
            @endforeach
        </div>
    </section>
    @endif

    {{-- Grid: Artigos + Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Artigos --}}
        <div class="lg:col-span-2">

            {{-- Filtros --}}
            <div class="mb-8" x-data="categoryFilters()">
                <div class="bg-bg-surface border border-border-subtle rounded-xl p-6 shadow-sm">

                    {{-- Busca --}}
                    <div class="relative mb-4">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-low"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               x-model="search"
                               placeholder="Buscar por título..."
                               class="w-full pl-12 pr-4 py-3 bg-bg-elevated border border-border-subtle rounded-lg
                                      text-text-high placeholder:text-text-low
                                      focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20
                                      transition-all duration-150">
                        <button x-show="search"
                                @click="search = ''"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-text-low hover:text-text-high">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Toggle mobile --}}
                    <button @click="filtersOpen = !filtersOpen"
                            class="lg:hidden flex items-center justify-center gap-2 w-full px-4 py-2.5 mb-4
                                   bg-bg-elevated text-text-medium hover:text-text-high rounded-lg
                                   transition-colors duration-150 border border-border-subtle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        <span class="text-sm font-medium" x-text="filtersOpen ? 'Ocultar Filtros' : 'Mostrar Filtros'"></span>
                    </button>

                    {{-- Filtros --}}
                    <div :class="filtersOpen ? 'grid' : 'hidden lg:grid'"
                         class="grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        {{-- Subcategoria --}}
                        @if ($category->children->isNotEmpty())
                        <div>
                            <label class="block text-xs font-medium text-text-medium mb-2 uppercase tracking-wide">
                                Subcategoria
                            </label>
                            <select x-model="subcategory"
                                    class="w-full px-3 py-2.5 bg-bg-elevated border border-border-subtle rounded-lg
                                           text-sm text-text-high focus:outline-none focus:border-accent
                                           focus:ring-2 focus:ring-accent/20 transition-all duration-150 cursor-pointer">
                                <option value="">Todas</option>
                                @foreach ($category->children as $child)
                                <option value="{{ $child->slug }}">{{ $child->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Ordenação --}}
                        <div>
                            <label class="block text-xs font-medium text-text-medium mb-2 uppercase tracking-wide">
                                Ordenar por
                            </label>
                            <select x-model="sortBy"
                                    class="w-full px-3 py-2.5 bg-bg-elevated border border-border-subtle rounded-lg
                                           text-sm text-text-high focus:outline-none focus:border-accent
                                           focus:ring-2 focus:ring-accent/20 transition-all duration-150 cursor-pointer">
                                <option value="recent">Mais Recentes</option>
                                <option value="oldest">Mais Antigos</option>
                                <option value="title-asc">Título (A-Z)</option>
                                <option value="title-desc">Título (Z-A)</option>
                            </select>
                        </div>

                        {{-- Limpar --}}
                        <div class="flex items-end">
                            <button @click="reset()"
                                    :disabled="!hasFilters"
                                    class="w-full px-4 py-2.5 bg-bg-elevated border border-border-subtle
                                           text-text-medium hover:text-text-high hover:border-accent
                                           rounded-lg text-sm font-medium transition-all duration-150
                                           disabled:opacity-50 disabled:cursor-not-allowed">
                                Limpar Filtros
                            </button>
                        </div>
                    </div>

                    {{-- Indicador de filtros ativos --}}
                    <div x-show="hasFilters" class="mt-4 pt-4 border-t border-border-subtle">
                        <p class="text-xs text-text-medium flex items-center gap-1">
                            <span class="w-2 h-2 bg-accent rounded-full inline-block"></span>
                            Filtros ativos
                        </p>
                    </div>
                </div>
            </div>

            {{-- Lista de artigos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($posts as $post)
                    @include('partials.article-card', ['post' => $post])
                @empty
                <div class="col-span-2 text-center py-16">
                    <p class="text-text-medium text-lg">Nenhum artigo encontrado nesta categoria.</p>
                </div>
                @endforelse
            </div>

            {{-- Paginação --}}
            @if ($posts->hasPages())
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                @include('partials.sidebar', [
                    'popularPosts'    => $posts->take(5),
                    'featuredTopList' => $topLists->first(),
                ])
            </div>
        </div>

    </div>
</div>

<script>
function categoryFilters() {
    return {
        search: '',
        subcategory: '',
        sortBy: 'recent',
        filtersOpen: false,
        get hasFilters() {
            return this.search !== '' || this.subcategory !== '' || this.sortBy !== 'recent';
        },
        reset() {
            this.search = '';
            this.subcategory = '';
            this.sortBy = 'recent';
        }
    }
}
</script>
@endsection