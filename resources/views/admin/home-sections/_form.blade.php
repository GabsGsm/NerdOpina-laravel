{{-- Campos do formulário de seção home (create e edit) --}}
{{-- Requer $categories. $homeSection pode ser null (create) ou um HomeSection (edit). --}}

<div x-data="{
    postType: '{{ old('post_type', $homeSection?->post_type ?? 'article') }}',
    showViewAll: {{ old('show_view_all', $homeSection?->show_view_all ?? false) ? 'true' : 'false' }},
}">

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
    <label class="block text-sm font-medium text-gray-700 mb-1">Layout *</label>
    <div class="flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="layout" value="card"
                   {{ old('layout', $homeSection?->layout ?? 'card') === 'card' ? 'checked' : '' }}
                   class="text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm text-gray-700">Cards (grade)</span>
        </label>
        <label x-show="postType === 'article'" class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="layout" value="list"
                   {{ old('layout', $homeSection?->layout) === 'list' ? 'checked' : '' }}
                   class="text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm text-gray-700">Lista</span>
        </label>
    </div>
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
