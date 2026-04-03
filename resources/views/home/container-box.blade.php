<div class="site-container pb-12">
    <div class="flex flex-col gap-12">
        @forelse ($container->sections as $section)
            @if ($section->posts->isNotEmpty())
                @include('home.section', ['section' => $section, 'cardCols' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'])
            @endif
        @empty
            <p class="text-text-low text-sm py-10 text-center">Nenhuma seção configurada.</p>
        @endforelse
    </div>
</div>
