@extends('admin.layouts.app')

@section('title', 'Novo Post')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
    <a href="{{ route('admin.posts.index') }}" class="hover:text-gray-700">Posts</a>
    <span>/</span>
    <span class="text-gray-900 font-medium">Novo</span>
</div>

<div class="max-w-4xl">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Novo Post</h1>

    <form method="POST" action="{{ route('admin.posts.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf

        @include('admin.posts._form', ['post' => null])

        <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
                Criar Post
            </button>
            <a href="{{ route('admin.posts.index') }}"
               class="px-5 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
