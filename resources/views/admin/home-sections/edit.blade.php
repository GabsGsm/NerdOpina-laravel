@extends('admin.layouts.app')

@section('title', 'Editar Seção Home')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
    <a href="{{ route('admin.home-containers.index') }}" class="hover:text-gray-700">Layout Home</a>
    <span>/</span>
    <span class="text-gray-900 font-medium">Editar</span>
</div>

<div class="max-w-3xl">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Editar Seção: {{ $homeSection->title }}</h1>

    <form method="POST" action="{{ route('admin.home-sections.update', $homeSection) }}"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('admin.home-sections._form')

        <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
                Salvar Alterações
            </button>
            <a href="{{ route('admin.home-containers.index') }}"
               class="px-5 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
