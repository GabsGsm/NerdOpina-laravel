{{-- Campos compartilhados entre create e edit --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
    <input type="text" name="name" value="{{ old('name', $category?->name) }}" required
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
    <input type="text" name="slug" value="{{ old('slug', $category?->slug) }}" required
           pattern="[a-z0-9\-]+"
           placeholder="exemplo-de-slug"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('slug') border-red-400 @enderror">
    @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria Pai</label>
    <select name="parent_id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">— Nenhuma (categoria raiz) —</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}" {{ old('parent_id', $category?->parent_id) == $parent->id ? 'selected' : '' }}>
                {{ $parent->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
    <textarea name="description" rows="3"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $category?->description) }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
    <div class="flex items-center gap-3">
        <input type="color" name="color" value="{{ old('color', $category?->color ?? '#6366f1') }}"
               class="h-9 w-16 rounded border border-gray-300 cursor-pointer">
        <span class="text-xs text-gray-400">Cor usada no badge e na página da categoria</span>
    </div>
    @error('color')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="border-t border-gray-100 pt-5">
    <h3 class="text-sm font-medium text-gray-700 mb-4">SEO (opcional)</h3>
    <div class="space-y-4">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $category?->meta_title) }}" maxlength="255"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Meta Description</label>
            <textarea name="meta_description" rows="2" maxlength="500"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('meta_description', $category?->meta_description) }}</textarea>
        </div>
    </div>
</div>
