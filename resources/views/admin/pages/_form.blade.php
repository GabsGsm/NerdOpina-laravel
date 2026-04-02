{{-- Campos compartilhados entre create e edit --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
    <input type="text" name="title" value="{{ old('title', $page?->title) }}" required
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-400 @enderror">
    @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
    <input type="text" name="slug" value="{{ old('slug', $page?->slug) }}" required
           pattern="[a-z0-9\-]+"
           placeholder="exemplo-de-slug"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('slug') border-red-400 @enderror">
    @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo *</label>
    <x-admin.editor
        name="content"
        :value="old('content', $page?->content ?? '')"
        :upload-url="route('admin.editor.upload-image')"
        :required="true"
    />
</div>

<div class="border-t border-gray-100 pt-5">
    <h3 class="text-sm font-medium text-gray-700 mb-4">SEO (opcional)</h3>
    <div class="space-y-4">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $page?->meta_title) }}" maxlength="255"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Meta Description</label>
            <textarea name="meta_description" rows="2" maxlength="500"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('meta_description', $page?->meta_description) }}</textarea>
        </div>
    </div>
</div>
