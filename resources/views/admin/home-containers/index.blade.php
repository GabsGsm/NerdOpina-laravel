@extends('admin.layouts.app')

@section('title', 'Layout da Home')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Layout da Home</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $containers->count() }} {{ Str::plural('container', $containers->count()) }} configurado{{ $containers->count() !== 1 ? 's' : '' }}</p>
    </div>

    {{-- Novo container --}}
    <div x-data="{ open: false }">
        <button @click="open = !open"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
            + Novo Container
        </button>
        <div x-show="open" x-cloak @click.outside="open = false"
             class="absolute right-6 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-lg p-4 z-20">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Criar Container</h3>
            <form method="POST" action="{{ route('admin.home-containers.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipo *</label>
                    <select name="type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="box">Box — largura total, seções empilhadas</option>
                        <option value="two_column">Duas Colunas — conteúdo + sidebar</option>
                    </select>
                </div>
                <input type="hidden" name="is_active" value="1">
                <button type="submit"
                        class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Criar
                </button>
            </form>
        </div>
    </div>
</div>

@if (session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
    {{ session('success') }}
</div>
@endif

<div class="space-y-4">
    @forelse ($containers as $container)
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- Cabeçalho do container --}}
        <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center gap-3">
                {{-- Move up/down --}}
                <div class="flex flex-col gap-0.5">
                    <form method="POST" action="{{ route('admin.home-containers.move-up', $container) }}">
                        @csrf
                        <button type="submit" {{ $loop->first ? 'disabled' : '' }}
                                class="p-1 text-gray-400 hover:text-gray-700 disabled:opacity-30 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.home-containers.move-down', $container) }}">
                        @csrf
                        <button type="submit" {{ $loop->last ? 'disabled' : '' }}
                                class="p-1 text-gray-400 hover:text-gray-700 disabled:opacity-30 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </form>
                </div>

                {{-- Badge tipo --}}
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $container->type === 'two_column' ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700' }}">
                    {{ $container->type === 'two_column' ? 'Duas Colunas' : 'Box' }}
                </span>

                <span class="text-xs text-gray-400">
                    {{ $container->sections_count }} seção(ões)
                    @if ($container->type === 'two_column')
                        · {{ $container->sidebar_widgets_count }} widget(s)
                    @endif
                </span>
            </div>

            <div class="flex items-center gap-3">
                {{-- Toggle ativo --}}
                <form method="POST" action="{{ route('admin.home-containers.update', $container) }}" class="flex items-center gap-1.5">
                    @csrf @method('PATCH')
                    <input type="hidden" name="type" value="{{ $container->type }}">
                    <input type="hidden" name="is_active" value="{{ $container->is_active ? '0' : '1' }}">
                    <button type="submit"
                            class="text-xs px-2 py-0.5 rounded-full font-medium transition-colors
                                   {{ $container->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        {{ $container->is_active ? 'Ativo' : 'Inativo' }}
                    </button>
                </form>

                {{-- Delete --}}
                <button type="button"
                        @click="$dispatch('confirm-delete', { url: '{{ route('admin.home-containers.destroy', $container) }}' })"
                        class="text-red-500 hover:text-red-400 text-xs font-medium transition-colors">
                    Excluir
                </button>
            </div>
        </div>

        {{-- Corpo: seções + (sidebar para two_column) --}}
        <div class="{{ $container->type === 'two_column' ? 'grid grid-cols-2 divide-x divide-gray-200' : '' }}">

            {{-- Painel de seções --}}
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Seções</h3>
                    <a href="{{ route('admin.home-sections.create', ['container_id' => $container->id]) }}"
                       class="text-xs text-indigo-600 hover:text-indigo-500 font-medium transition-colors">
                        + Adicionar seção
                    </a>
                </div>

                @if ($container->sections->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-4">Nenhuma seção. Adicione a primeira.</p>
                @else
                    <div class="space-y-1.5">
                        @foreach ($container->sections as $section)
                        <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="flex items-center gap-2 min-w-0">
                                {{-- Move --}}
                                <div class="flex flex-col gap-0.5 shrink-0">
                                    <form method="POST" action="{{ route('admin.home-sections.move-up', $section) }}">
                                        @csrf
                                        <button type="submit" {{ $loop->first ? 'disabled' : '' }}
                                                class="p-0.5 text-gray-300 hover:text-gray-600 disabled:opacity-30 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.home-sections.move-down', $section) }}">
                                        @csrf
                                        <button type="submit" {{ $loop->last ? 'disabled' : '' }}
                                                class="p-0.5 text-gray-300 hover:text-gray-600 disabled:opacity-30 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $section->title }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-xs px-1.5 py-0 rounded font-medium
                                            {{ $section->post_type === 'toplist' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                            {{ $section->post_type === 'toplist' ? 'Toplist' : 'Artigo' }}
                                        </span>
                                        <span class="text-xs px-1.5 py-0 rounded bg-gray-100 text-gray-500 font-medium">
                                            {{ $section->layout === 'list' ? 'Lista' : 'Cards' }}
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $section->limit }} posts</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 ml-2">
                                <span class="text-xs px-1.5 py-0 rounded font-medium
                                    {{ $section->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $section->is_active ? 'Ativa' : 'Inativa' }}
                                </span>
                                <a href="{{ route('admin.home-sections.edit', $section) }}"
                                   class="text-xs text-indigo-600 hover:text-indigo-500 font-medium">Editar</a>
                                <button type="button"
                                        @click="$dispatch('confirm-delete', { url: '{{ route('admin.home-sections.destroy', $section) }}' })"
                                        class="text-xs text-red-500 hover:text-red-400 font-medium">Excluir</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Painel sidebar (only two_column) --}}
            @if ($container->type === 'two_column')
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Widgets Sidebar</h3>
                </div>

                @if ($container->sidebarWidgets->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-2">Nenhum widget adicionado.</p>
                @else
                    <div class="space-y-1.5 mb-3">
                        @foreach ($container->sidebarWidgets as $widget)
                        <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="flex flex-col gap-0.5 shrink-0">
                                    <form method="POST" action="{{ route('admin.home-containers.widgets.move-up', [$container, $widget]) }}">
                                        @csrf
                                        <button type="submit" {{ $loop->first ? 'disabled' : '' }}
                                                class="p-0.5 text-gray-300 hover:text-gray-600 disabled:opacity-30 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.home-containers.widgets.move-down', [$container, $widget]) }}">
                                        @csrf
                                        <button type="submit" {{ $loop->last ? 'disabled' : '' }}
                                                class="p-0.5 text-gray-300 hover:text-gray-600 disabled:opacity-30 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">
                                        {{ $widget->title ?? match($widget->type) {
                                            'popular_posts'    => 'Posts Populares',
                                            'featured_toplist' => 'Toplist Destaque',
                                            default            => 'Categorias',
                                        } }}
                                    </p>
                                    <span class="text-xs text-gray-400">{{ $widget->type }}</span>
                                </div>
                            </div>
                            <button type="button"
                                    @click="$dispatch('confirm-delete', { url: '{{ route('admin.home-containers.widgets.destroy', [$container, $widget]) }}' })"
                                    class="text-xs text-red-500 hover:text-red-400 font-medium shrink-0 ml-2">
                                Remover
                            </button>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Form para adicionar widget --}}
                <form method="POST" action="{{ route('admin.home-containers.widgets.store', $container) }}"
                      class="flex items-end gap-2 border-t border-gray-100 pt-3">
                    @csrf
                    <input type="hidden" name="is_active" value="1">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Adicionar widget</label>
                        <select name="type"
                                class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="popular_posts">Posts Populares</option>
                            <option value="categories">Categorias</option>
                            <option value="featured_toplist">Toplist Destaque</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-medium rounded-lg transition-colors">
                        Adicionar
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 px-5 py-10 text-center text-gray-400 text-sm">
        Nenhum container configurado. Crie o primeiro para montar o layout da home.
    </div>
    @endforelse
</div>

@endsection
