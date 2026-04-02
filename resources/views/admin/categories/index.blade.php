@extends('admin.layouts.app')

@section('title', 'Categorias')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Categorias</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $categories->count() }} categorias</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        + Nova Categoria
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Nome</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Slug</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Pai</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Posts</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Cor</th>
                <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wide">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                    <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $category->parent->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $category->posts_count }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-block w-5 h-5 rounded-full border border-gray-200" style="background-color: {{ $category->color }};"></span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="text-indigo-600 hover:text-indigo-500 font-medium text-xs">Editar</a>
                            <button type="button"
                                    @click="$dispatch('confirm-delete', { url: '{{ route('admin.categories.destroy', $category) }}' })"
                                    class="text-red-500 hover:text-red-400 font-medium text-xs">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">Nenhuma categoria cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
