@props(['name' => 'content', 'value' => '', 'uploadUrl', 'placeholder' => 'Escreva aqui...', 'required' => false])

<div class="editor-wrapper relative border {{ $errors->has($name) ? 'border-red-400' : 'border-gray-300' }} rounded-lg">

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-0.5 p-2 bg-gray-50 border-b border-gray-200">
        <button type="button" data-cmd="bold" title="Negrito" class="editor-btn font-bold">B</button>
        <button type="button" data-cmd="italic" title="Itálico" class="editor-btn italic">I</button>
        <button type="button" data-cmd="strike" title="Riscado" class="editor-btn line-through">S</button>
        <button type="button" data-cmd="code" title="Código inline" class="editor-btn font-mono">{ }</button>
        <span class="w-px h-5 bg-gray-300 mx-1"></span>
        <button type="button" data-cmd="h2" title="Título H2" class="editor-btn font-bold">H2</button>
        <button type="button" data-cmd="h3" title="Título H3" class="editor-btn font-bold">H3</button>
        <button type="button" data-cmd="blockquote" title="Citação" class="editor-btn">"</button>
        <span class="w-px h-5 bg-gray-300 mx-1"></span>
        <button type="button" data-cmd="bullet" title="Lista" class="editor-btn">• —</button>
        <button type="button" data-cmd="ordered" title="Lista numerada" class="editor-btn">1.</button>
        <button type="button" data-cmd="hr" title="Separador" class="editor-btn">—</button>
        <span class="w-px h-5 bg-gray-300 mx-1"></span>
        <button type="button" data-cmd="link" title="Link" class="editor-btn">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
        </button>
        <label title="Inserir imagem" class="editor-btn cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <input type="file" data-image-upload accept=".jpg,.jpeg,.png,.webp,.gif" class="hidden">
        </label>
        <span class="w-px h-5 bg-gray-300 mx-1"></span>
        <button type="button" data-cmd="undo" title="Desfazer" class="editor-btn">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        </button>
        <button type="button" data-cmd="redo" title="Refazer" class="editor-btn">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/></svg>
        </button>
    </div>

    {{-- Área editável --}}
    <div data-editor
         data-content="{{ $value }}"
         data-upload-url="{{ $uploadUrl }}"
         data-placeholder="{{ $placeholder }}"
         class="min-h-64 p-4 prose prose-sm max-w-none focus:outline-none bg-white"></div>

    {{-- Textarea oculta para submissão (deve ficar dentro do .editor-wrapper para o JS encontrar) --}}
    <textarea name="{{ $name }}" data-editor-output class="hidden" @if($required) required @endif>{{ $value }}</textarea>
</div>

@error($name)
    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
@enderror
