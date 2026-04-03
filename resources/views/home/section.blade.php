<section>
    {{-- Cabeçalho da seção --}}
    @if ($section->show_title || $section->show_view_all)
    <div class="flex items-center justify-between mb-5">
        @if ($section->show_title)
        <div class="flex items-center gap-3">
            <div class="w-1 h-7 rounded-full"
                 @if($section->accent_color)
                     style="background-color: {{ $section->accent_color }}"
                 @else
                     style="background-color: var(--color-accent)"
                 @endif
            ></div>
            <h2 class="font-heading text-xl md:text-2xl font-bold text-text-high">
                {{ $section->title }}
            </h2>
        </div>
        @endif

        @if ($section->show_view_all && $section->view_all_url)
        <a href="{{ $section->view_all_url }}"
           class="text-sm text-accent hover:opacity-75 transition-opacity duration-150 font-medium">
            Ver todos →
        </a>
        @endif
    </div>
    @endif

    {{-- Conteúdo: cards ou lista --}}
    @if ($section->post_type === 'toplist' || $section->layout === 'card')
        <div class="grid {{ $cardCols ?? 'grid-cols-1 sm:grid-cols-2' }} gap-5">
            @foreach ($section->posts as $post)
                @if ($section->post_type === 'toplist')
                    @include('partials.toplist-card', ['post' => $post])
                @else
                    @include('partials.article-card', ['post' => $post])
                @endif
            @endforeach
        </div>
    @else
        <div class="bg-bg-surface rounded-xl border border-border-subtle px-5 divide-y divide-border-subtle">
            @foreach ($section->posts as $post)
                @include('partials.news-item', ['post' => $post])
            @endforeach
        </div>
    @endif
</section>
