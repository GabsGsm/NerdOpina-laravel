@extends('admin.layouts.app')

@section('title', 'Posts')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Posts</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $posts->total() }} posts encontrados</p>
    </div>
    <a href="{{ route('admin.posts.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        + Novo Post
    </a>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.posts.index') }}"
      class="flex flex-wrap items-center gap-3 mb-5">
    <select name="type"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Tipo: todos</option>
        <option value="article"  {{ request('type') === 'article'  ? 'selected' : '' }}>Artigo</option>
        <option value="toplist"  {{ request('type') === 'toplist'  ? 'selected' : '' }}>Toplist</option>
    </select>
    <select name="status"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Status: todos</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
        <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Rascunho</option>
    </select>
    <select name="category"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Categoria: todas</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    <button type="submit"
            class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
        Filtrar
    </button>
    @if(request()->hasAny(['type','status','category']))
        <a href="{{ route('admin.posts.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">Limpar</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Título</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Tipo</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Categoria</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Publicado em</th>
                <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wide">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($posts as $post)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900 line-clamp-1">{{ $post->title }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $post->slug }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $post->type === 'toplist' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $post->type === 'toplist' ? 'Toplist' : 'Artigo' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $post->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $post->status === 'published' ? 'Publicado' : 'Rascunho' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $post->category->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $post->published_at?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ $post->getUrl() }}" target="_blank"
                               class="text-gray-400 hover:text-gray-600 text-xs">Ver</a>
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="text-indigo-600 hover:text-indigo-500 font-medium text-xs">Editar</a>
                            <button type="button"
                                    @click="$dispatch('confirm-delete', { url: '{{ route('admin.posts.destroy', $post) }}' })"
                                    class="text-red-500 hover:text-red-400 font-medium text-xs">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">Nenhum post encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $posts->withQueryString()->links() }}
</div>

@endsection
