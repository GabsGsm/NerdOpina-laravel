{{-- Campos do formulário de post (create e edit) --}}
{{-- Requer $categories. $post pode ser null (create) ou um Post (edit). --}}

<div x-data="{
    postType: '{{ old('type', $post?->type ?? 'article') }}',
    hasImage: {{ $post?->featured_image ? 'true' : 'false' }},
    previewUrl: '{{ $post?->getFeaturedImageUrl() ?? '' }}'
}">

{{-- Linha 1: Tipo + Categoria --}}
<div class="grid grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
        <select name="type" x-model="postType" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="article">Artigo</option>
            <option value="toplist">Toplist</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Categoria *</label>
        <select name="category_id" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('category_id') border-red-400 @enderror">
            <option value="">— Selecione —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $post?->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Título --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
    <input type="text" name="title" value="{{ old('title', $post?->title) }}" required maxlength="255"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-400 @enderror">
    @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Slug --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
    <input type="text" name="slug" value="{{ old('slug', $post?->slug) }}" required
           pattern="[a-z0-9\-]+" placeholder="titulo-do-post"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('slug') border-red-400 @enderror">
    @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Excerpt --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt * <span class="text-gray-400 font-normal">(resumo para listagens)</span></label>
    <textarea name="excerpt" rows="3" required maxlength="500"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $post?->excerpt) }}</textarea>
    @error('excerpt')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Conteúdo --}}
<div>
    {{-- Nota toplist --}}
    <div x-show="postType === 'toplist'" x-cloak
         class="flex items-start gap-3 p-4 mb-3 bg-indigo-50 border border-indigo-200 rounded-lg">
        <svg class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-indigo-800">Os itens da lista são o conteúdo principal</p>
            <p class="text-xs text-indigo-600 mt-0.5">O campo abaixo é opcional — use para adicionar introdução ou conclusão ao redor da lista.</p>
        </div>
    </div>

    <label class="block text-sm font-medium text-gray-700 mb-1">
        <span x-show="postType === 'article'">Conteúdo (HTML) *</span>
        <span x-show="postType === 'toplist'" x-cloak>Introdução / Conclusão <span class="text-gray-400 font-normal">(opcional)</span></span>
    </label>
    <textarea name="content"
              :rows="postType === 'toplist' ? 6 : 16"
              :required="postType === 'article'"
              class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('content') ? 'border-red-400' : 'border-gray-300' }}">{{ old('content', $post?->content) }}</textarea>
    @error('content')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

{{-- Imagem Destaque --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Imagem Destaque</label>

    <div x-show="hasImage" class="mb-3">
        <img :src="previewUrl" alt="Preview" class="h-32 w-auto rounded-lg border border-gray-200 object-cover">
        <p class="text-xs text-gray-400 mt-1">Imagem atual. Selecione um novo arquivo para substituir.</p>
    </div>

    <input type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp"
           @change="
               if ($event.target.files[0]) {
                   previewUrl = URL.createObjectURL($event.target.files[0]);
                   hasImage = true;
               }
           "
           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    <p class="text-xs text-gray-400 mt-1">JPG, PNG ou WebP. Máx. 2MB.</p>
    @error('featured_image')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Alt da Imagem</label>
    <input type="text" name="featured_image_alt" value="{{ old('featured_image_alt', $post?->featured_image_alt) }}" maxlength="255"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
</div>

{{-- Status + Data de Publicação --}}
<div class="grid grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
        <select name="status" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="draft"     {{ old('status', $post?->status ?? 'draft') === 'draft'     ? 'selected' : '' }}>Rascunho</option>
            <option value="published" {{ old('status', $post?->status ?? 'draft') === 'published' ? 'selected' : '' }}>Publicado</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicação</label>
        <input type="datetime-local" name="published_at"
               value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i')) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
</div>

{{-- TopList Items (visível apenas quando type = toplist) --}}
<div x-show="postType === 'toplist'" x-cloak
     x-data="toplistManager({{ $post?->topListItems ? json_encode($post->topListItems->toArray()) : '[]' }}, '{{ url('/img') }}/')">

    <div class="border-t border-gray-100 pt-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Itens da Toplist</h3>
            <button type="button" @click="addItem()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition-colors">
                + Adicionar Item
            </button>
        </div>

        <div class="space-y-3" id="toplist-items">
            <template x-for="(item, index) in items" :key="item._key">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">

                    {{-- Inputs hidden para submissão --}}
                    <input type="hidden" :name="`items[${index}][rank]`" :value="index + 1">
                    <input type="hidden" :name="`items[${index}][title]`" :value="item.title">
                    <input type="hidden" :name="`items[${index}][image_url]`" :value="item.image_url ?? ''">
                    <input type="hidden" :name="`items[${index}][external_url]`" :value="item.external_url ?? ''">
                    <input type="hidden" :name="`items[${index}][description]`" :value="item.description ?? ''">

                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 bg-indigo-600 text-white text-xs font-bold rounded-full flex items-center justify-center shrink-0" x-text="index + 1"></span>
                        <span class="text-xs text-gray-500">Item da lista</span>
                        <div class="ml-auto flex items-center gap-2">
                            <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                    class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" @click="moveDown(index)" :disabled="index === items.length - 1"
                                    class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <button type="button" @click="removeItem(index)"
                                    class="p-1 text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-500 mb-1">Título *</label>
                            <input type="text" x-model="item.title" placeholder="Nome do item" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-500 mb-2">Imagem</label>

                            {{-- Preview --}}
                            <div x-show="item.previewUrl || item.image_url" class="mb-2 flex items-center gap-2">
                                <img :src="item.previewUrl || getItemImageSrc(item.image_url)"
                                     alt="" class="w-14 h-14 rounded-lg object-cover border border-gray-200 shrink-0">
                                <div class="text-xs text-gray-500">
                                    <span x-show="item.previewUrl" class="text-indigo-600 font-medium">Nova imagem selecionada</span>
                                    <span x-show="!item.previewUrl && item.image_url">Imagem atual</span>
                                    <button type="button"
                                            @click="item.image_url = ''; item.previewUrl = null"
                                            class="block mt-1 text-red-400 hover:text-red-600">
                                        Remover
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <input type="file" :name="`items_images[${index}]`" accept=".jpg,.jpeg,.png,.webp"
                                           @change="
                                               if ($event.target.files[0]) {
                                                   item.previewUrl = URL.createObjectURL($event.target.files[0]);
                                                   item.image_url = '';
                                               }
                                           "
                                           class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="text-xs text-gray-400 mt-0.5">Upload local</p>
                                </div>
                                <div>
                                    <input type="text" x-model="item.image_url" placeholder="https://..."
                                           class="w-full border border-gray-200 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <p class="text-xs text-gray-400 mt-0.5">ou URL externa</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Link Externo</label>
                            <input type="url" x-model="item.external_url" placeholder="https://..."
                                   class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-500 mb-1">Descrição</label>
                            <textarea x-model="item.description" rows="2" placeholder="Descrição curta do item"
                                      class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="items.length === 0">
                <p class="text-sm text-gray-400 py-4 text-center border-2 border-dashed border-gray-200 rounded-lg">
                    Nenhum item. Clique em "+ Adicionar Item" para começar.
                </p>
            </template>
        </div>
    </div>
</div>

{{-- SEO --}}
<div class="border-t border-gray-100 pt-5">
    <h3 class="text-sm font-semibold text-gray-700 mb-4">SEO (opcional)</h3>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $post?->meta_title) }}" maxlength="255"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">OG Title</label>
                <input type="text" name="og_title" value="{{ old('og_title', $post?->og_title) }}" maxlength="255"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Meta Description</label>
            <textarea name="meta_description" rows="2" maxlength="500"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('meta_description', $post?->meta_description) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm text-gray-600 mb-1">URL Canônica</label>
                <input type="url" name="canonical_url" value="{{ old('canonical_url', $post?->canonical_url) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Robots</label>
                <select name="robots"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="index,follow"      {{ old('robots', $post?->robots ?? 'index,follow') === 'index,follow'      ? 'selected' : '' }}>index, follow</option>
                    <option value="noindex,nofollow"  {{ old('robots', $post?->robots) === 'noindex,nofollow'  ? 'selected' : '' }}>noindex, nofollow</option>
                    <option value="noindex,follow"    {{ old('robots', $post?->robots) === 'noindex,follow'    ? 'selected' : '' }}>noindex, follow</option>
                </select>
            </div>
        </div>
    </div>
</div>

</div>{{-- fim x-data --}}

<script>
function toplistManager(existingItems, imgBaseUrl) {
    return {
        imgBaseUrl: imgBaseUrl,
        items: existingItems.map((item, i) => ({
            ...item,
            _key: Date.now() + i,
            previewUrl: null,
        })),

        getItemImageSrc(imageUrl) {
            if (!imageUrl) return null;
            if (imageUrl.startsWith('http')) return imageUrl;
            return this.imgBaseUrl + imageUrl;
        },

        addItem() {
            this.items.push({
                _key: Date.now(),
                rank: this.items.length + 1,
                title: '',
                image_url: '',
                external_url: '',
                description: '',
                previewUrl: null,
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        moveUp(index) {
            if (index === 0) { return; }
            [this.items[index - 1], this.items[index]] = [this.items[index], this.items[index - 1]];
        },

        moveDown(index) {
            if (index === this.items.length - 1) { return; }
            [this.items[index], this.items[index + 1]] = [this.items[index + 1], this.items[index]];
        },
    };
}
</script>
