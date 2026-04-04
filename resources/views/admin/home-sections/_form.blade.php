{{-- Campos do formulário de seção home (create e edit) --}}
{{-- Requer $categories, $containers. $homeSection pode ser null (create) ou um HomeSection (edit). --}}

<div x-data="{
    postType: '{{ old('post_type', $homeSection?->post_type ?? 'article') }}',
    layout: '{{ old('layout', $homeSection?->layout ?? 'card') }}',
    showViewAll: {{ old('show_view_all', $homeSection?->show_view_all ?? false) ? 'true' : 'false' }},
    get limitHint() {
        const hints = { editorial_hero: '1 post recomendado', featured_stack: '3 posts recomendados', featured_list: '3 posts recomendados', grid_cards: '3 posts recomendados' };
        return hints[this.layout] ?? null;
    },
}">

{{-- Container --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Container *</label>
    @if ($selectedContainerId ?? false)
        <input type="hidden" name="container_id" value="{{ $selectedContainerId }}">
        <p class="text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
            Container #{{ $selectedContainerId }}
        </p>
    @else
        <select name="container_id" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('container_id') border-red-400 @enderror">
            <option value="">Selecione um container...</option>
            @foreach ($containers as $c)
                <option value="{{ $c->id }}"
                    {{ old('container_id', $homeSection?->container_id) == $c->id ? 'selected' : '' }}>
                    #{{ $c->id }} — {{ $c->type === 'two_column' ? 'Duas Colunas' : 'Box' }}
                    ({{ $c->sections->count() }} seção(ões))
                </option>
            @endforeach
        </select>
    @endif
    @error('container_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Linha 1: Título + Tipo --}}
<div class="grid grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
        <input type="text" name="title" value="{{ old('title', $homeSection?->title) }}" required maxlength="255"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-400 @enderror">
        @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Post *</label>
        <select name="post_type" x-model="postType" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="article">Artigo</option>
            <option value="toplist">Toplist</option>
        </select>
        @error('post_type')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Linha 2: Layout --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Layout *</label>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        {{-- Card --}}
        <label class="relative cursor-pointer">
            <input type="radio" name="layout" value="card" x-model="layout"
                   class="sr-only peer">
            <div class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-lg text-center transition-all duration-150
                        border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300">
                <svg class="w-5 h-5 text-gray-500 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="text-xs font-medium text-gray-700">Cards</span>
            </div>
        </label>

        {{-- Lista --}}
        <label class="relative cursor-pointer" x-show="postType === 'article'">
            <input type="radio" name="layout" value="list" x-model="layout"
                   class="sr-only peer">
            <div class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-lg text-center transition-all duration-150
                        border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <span class="text-xs font-medium text-gray-700">Lista</span>
            </div>
        </label>

        {{-- Editorial Destaque --}}
        <label class="relative cursor-pointer" x-show="postType === 'article'">
            <input type="radio" name="layout" value="editorial_hero" x-model="layout"
                   class="sr-only peer">
            <div class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-lg text-center transition-all duration-150
                        border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
                <span class="text-xs font-medium text-gray-700">Editorial<br>Destaque</span>
            </div>
        </label>

        {{-- Destaque + Pilha --}}
        <label class="relative cursor-pointer" x-show="postType === 'article'">
            <input type="radio" name="layout" value="featured_stack" x-model="layout"
                   class="sr-only peer">
            <div class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-lg text-center transition-all duration-150
                        border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h9a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1v-2z"/>
                </svg>
                <span class="text-xs font-medium text-gray-700">Destaque<br>+ Pilha</span>
            </div>
        </label>

        {{-- Destaque + Lista Compacta --}}
        <label class="relative cursor-pointer" x-show="postType === 'article'">
            <input type="radio" name="layout" value="featured_list" x-model="layout"
                   class="sr-only peer">
            <div class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-lg text-center transition-all duration-150
                        border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h8a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h4a1 1 0 011 1v1a1 1 0 01-1 1h-4a1 1 0 01-1-1v-1zm0 4a1 1 0 011-1h4a1 1 0 011 1v1a1 1 0 01-1 1h-4a1 1 0 01-1-1v-1z"/>
                </svg>
                <span class="text-xs font-medium text-gray-700">Destaque<br>+ Lista</span>
            </div>
        </label>

        {{-- Grade Elevada --}}
        <label class="relative cursor-pointer" x-show="postType === 'article'">
            <input type="radio" name="layout" value="grid_cards" x-model="layout"
                   class="sr-only peer">
            <div class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-lg text-center transition-all duration-150
                        border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zm8 0a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zm-8 8a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zm8 0a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"/>
                </svg>
                <span class="text-xs font-medium text-gray-700">Grade<br>Elevada</span>
            </div>
        </label>
    </div>
    <p class="mt-2 text-xs text-indigo-600 font-medium" x-show="limitHint" x-text="'💡 ' + limitHint"></p>
    @error('layout')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Linha 3: Order By + Limit --}}
<div class="grid grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ordenação *</label>
        <select name="order_by" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="latest"  {{ old('order_by', $homeSection?->order_by ?? 'latest') === 'latest'  ? 'selected' : '' }}>Mais recentes</option>
            <option value="oldest" {{ old('order_by', $homeSection?->order_by) === 'oldest' ? 'selected' : '' }}>Mais antigos</option>
        </select>
        @error('order_by')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade de posts *</label>
        <input type="number" name="limit" min="1" max="20"
               value="{{ old('limit', $homeSection?->limit ?? 4) }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('limit') border-red-400 @enderror">
        @error('limit')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Filtro de Categorias --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por categorias <span class="text-gray-400 font-normal">(deixe vazio para todas)</span></label>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 p-3 border border-gray-200 rounded-lg bg-gray-50 max-h-44 overflow-y-auto">
        @foreach($categories as $cat)
            <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-gray-900">
                <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                       {{ in_array($cat->id, old('category_ids', $homeSection?->category_ids ?? [])) ? 'checked' : '' }}
                       class="rounded text-indigo-600 focus:ring-indigo-500">
                {{ $cat->name }}
            </label>
        @endforeach
    </div>
    @error('category_ids')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Cor de destaque --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Cor de destaque
        <span class="text-gray-400 font-normal">(barra lateral do título da seção)</span>
    </label>
    <div class="flex items-center gap-3">
        <input type="color" name="accent_color"
               value="{{ old('accent_color', $homeSection?->accent_color ?? '#6366f1') }}"
               class="h-9 w-16 cursor-pointer rounded-lg border border-gray-300 p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <span class="text-xs text-gray-400">Deixe o padrão do tema se não quiser personalizar</span>
    </div>
    @error('accent_color')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Opções de exibição --}}
<div class="border-t border-gray-100 pt-5">
    <h3 class="text-sm font-semibold text-gray-700 mb-4">Opções de Exibição</h3>

    <div class="space-y-3">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="show_title" value="0">
            <input type="checkbox" name="show_title" value="1"
                   {{ old('show_title', $homeSection?->show_title ?? true) ? 'checked' : '' }}
                   class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
            <span class="text-sm text-gray-700">Exibir título da seção</span>
        </label>

        <label class="flex items-center gap-3 cursor-pointer" @click="showViewAll = !showViewAll">
            <input type="hidden" name="show_view_all" value="0">
            <input type="checkbox" name="show_view_all" value="1"
                   x-model="showViewAll"
                   {{ old('show_view_all', $homeSection?->show_view_all ?? false) ? 'checked' : '' }}
                   class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
            <span class="text-sm text-gray-700">Exibir botão "Ver todos"</span>
        </label>

        <div x-show="showViewAll" class="ml-7">
            <label class="block text-sm text-gray-600 mb-1">URL do "Ver todos"</label>
            <input type="text" name="view_all_url" value="{{ old('view_all_url', $homeSection?->view_all_url) }}" maxlength="500"
                   placeholder="/categoria/artigos"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('view_all_url') border-red-400 @enderror">
            @error('view_all_url')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $homeSection?->is_active ?? true) ? 'checked' : '' }}
                   class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
            <span class="text-sm text-gray-700">Seção ativa (visível na home)</span>
        </label>
    </div>
</div>

</div>{{-- fim x-data --}}
