@extends('admin.layouts.app')

@section('title', 'Páginas')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Páginas</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $pages->count() }} páginas</p>
    </div>
    <a href="{{ route('admin.pages.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        + Nova Página
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Título</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Slug</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Atualizado em</th>
                <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wide">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $page)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $page->title }}</td>
                    <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $page->slug }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $page->updated_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.pages.edit', $page) }}"
                               class="text-indigo-600 hover:text-indigo-500 font-medium text-xs">Editar</a>
                            <button type="button"
                                    @click="$dispatch('confirm-delete', { url: '{{ route('admin.pages.destroy', $page) }}' })"
                                    class="text-red-500 hover:text-red-400 font-medium text-xs">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-gray-400 text-sm">Nenhuma página cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
