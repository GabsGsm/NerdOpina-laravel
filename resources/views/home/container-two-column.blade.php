<div class="site-container pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Área principal --}}
        <div class="lg:col-span-2 flex flex-col gap-12">
            @forelse ($container->sections as $section)
                @if ($section->posts->isNotEmpty())
                    @include('home.section', ['section' => $section, 'cardCols' => 'grid-cols-1 sm:grid-cols-2'])
                @endif
            @empty
                <p class="text-text-low text-sm py-10 text-center">Nenhuma seção configurada.</p>
            @endforelse
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-5">
                @forelse ($container->sidebarWidgets as $widget)
                    @include('home.widget', compact('widget'))
                @empty
                    <div class="bg-bg-surface rounded-xl p-5 border border-border-subtle text-center text-text-low text-sm">
                        Nenhum widget configurado.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
