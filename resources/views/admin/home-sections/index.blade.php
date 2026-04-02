@extends('admin.layouts.app')

@section('title', 'Seções Home')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Seções Home</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $sections->count() }} {{ Str::plural('seção', $sections->count()) }} configurada{{ $sections->count() !== 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('admin.home-sections.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
        + Nova Seção
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide w-16">Ordem</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Título</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Tipo</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Layout</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Limit</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wide">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sections as $section)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex flex-col gap-1">
                            <form method="POST" action="{{ route('admin.home-sections.move-up', $section) }}">
                                @csrf
                                <button type="submit"
                                        class="p-1 text-gray-400 hover:text-gray-700 transition-colors disabled:opacity-30"
                                        {{ $loop->first ? 'disabled' : '' }}>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                </button>
                            </form>
                            <span class="text-xs text-gray-400 font-mono text-center">{{ $section->position }}</span>
                            <form method="POST" action="{{ route('admin.home-sections.move-down', $section) }}">
                                @csrf
                                <button type="submit"
                                        class="p-1 text-gray-400 hover:text-gray-700 transition-colors disabled:opacity-30"
                                        {{ $loop->last ? 'disabled' : '' }}>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900">{{ $section->title }}</p>
                        @if($section->category_ids)
                            <p class="text-xs text-gray-400 mt-0.5">{{ count($section->category_ids) }} categoria(s)</p>
                        @else
                            <p class="text-xs text-gray-400 mt-0.5">Todas as categorias</p>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $section->post_type === 'toplist' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $section->post_type === 'toplist' ? 'Toplist' : 'Artigo' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-600">
                            {{ $section->layout === 'list' ? 'Lista' : 'Cards' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $section->limit }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $section->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $section->is_active ? 'Ativa' : 'Inativa' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.home-sections.edit', $section) }}"
                               class="text-indigo-600 hover:text-indigo-500 font-medium text-xs">Editar</a>
                            <button type="button"
                                    @click="$dispatch('confirm-delete', { url: '{{ route('admin.home-sections.destroy', $section) }}' })"
                                    class="text-red-500 hover:text-red-400 font-medium text-xs">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-gray-400 text-sm">
                        Nenhuma seção configurada. <a href="{{ route('admin.home-sections.create') }}" class="text-indigo-600 hover:underline">Criar primeira seção</a>.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
