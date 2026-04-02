@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-0.5">Visão geral do blog</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Total Posts</p>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_posts'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Publicados</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['published_posts'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Rascunhos</p>
        <p class="text-3xl font-bold text-amber-500">{{ $stats['draft_posts'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Categorias</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_categories'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Páginas</p>
        <p class="text-3xl font-bold text-gray-700">{{ $stats['total_pages'] }}</p>
    </div>
</div>

{{-- Ações rápidas --}}
<div class="flex flex-wrap gap-3 mb-8">
    <a href="{{ route('admin.posts.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        + Novo Post
    </a>
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
        + Nova Categoria
    </a>
    <a href="{{ route('admin.pages.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
        + Nova Página
    </a>
</div>

{{-- Posts recentes --}}
<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Posts Recentes</h2>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($recentPosts as $post)
            <div class="flex items-center gap-4 px-5 py-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $post->title }}</p>
                    <p class="text-xs text-gray-400">{{ $post->category->name ?? '—' }} · {{ $post->type }}</p>
                </div>
                <span class="shrink-0 text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $post->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $post->status === 'published' ? 'Publicado' : 'Rascunho' }}
                </span>
                <a href="{{ route('admin.posts.edit', $post) }}"
                   class="shrink-0 text-xs text-indigo-600 hover:text-indigo-500 font-medium">
                    Editar
                </a>
            </div>
        @empty
            <p class="px-5 py-6 text-sm text-gray-400 text-center">Nenhum post ainda.</p>
        @endforelse
    </div>
</div>

@endsection
