@extends('admin.layouts.app')

@section('title', 'Editar Post')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
    <a href="{{ route('admin.posts.index') }}" class="hover:text-gray-700">Posts</a>
    <span>/</span>
    <span class="text-gray-900 font-medium line-clamp-1">{{ $post->title }}</span>
</div>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-900">Editar Post</h1>
        <a href="{{ $post->getUrl() }}" target="_blank"
           class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            Ver no site
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('admin.posts._form', ['post' => $post])

        <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
                Salvar Alterações
            </button>
            <a href="{{ route('admin.posts.index') }}"
               class="px-5 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
