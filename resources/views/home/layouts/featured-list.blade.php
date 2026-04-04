{{-- Layout: Destaque + Lista Compacta — 1 card featured (col-span-3) + lista horizontal (col-span-2) --}}
@php
    $featured = $section->posts->first();
    $rest = $section->posts->skip(1)->values();
@endphp
@if ($featured)
<section>
    {{-- Cabeçalho --}}
    @if ($section->show_title || $section->show_view_all)
    <div class="flex items-center justify-between mb-6">
        @if ($section->show_title)
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 rounded-full"
                 style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}"></div>
            <h2 class="font-heading text-2xl md:text-3xl font-bold text-text-high">
                {{ $section->title }}
            </h2>
        </div>
        @endif
        @if ($section->show_view_all && $section->view_all_url)
        <a href="{{ $section->view_all_url }}"
           class="text-sm text-accent hover:opacity-75 transition-opacity duration-150">
            Ver todos →
        </a>
        @endif
    </div>
    @endif

    {{-- Grid: featured 3/5 + lista 2/5 --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Card em destaque --}}
        <a href="{{ $featured->getUrl() }}" class="group block lg:col-span-3">
            <article class="relative bg-bg-surface rounded-xl overflow-hidden border border-border-subtle transition-all duration-300 hover:shadow-xl h-full"
                     onmouseenter="this.style.borderColor='{{ $section->accent_color ?? 'var(--color-accent)' }}66'"
                     onmouseleave="this.style.borderColor=''">
                <div class="relative h-72 lg:h-80 overflow-hidden">
                    @if ($featured->featured_image)
                        <img src="{{ $featured->getFeaturedImageUrl() }}"
                             alt="{{ $featured->featured_image_alt ?? $featured->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy" decoding="async">
                    @else
                        <div class="w-full h-full bg-bg-elevated flex items-center justify-center">
                            <svg class="w-16 h-16 text-text-low" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-bg-base/95 via-bg-base/50 to-transparent"></div>

                    {{-- Tag Destaque da Semana --}}
                    <div class="absolute top-4 left-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full backdrop-blur-sm border"
                              style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}33; border-color: {{ $section->accent_color ?? 'var(--color-accent)' }}66; color: {{ $section->accent_color ?? 'var(--color-accent)' }}">
                            Destaque da Semana
                        </span>
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        @include('partials.category-badge', ['category' => $featured->category, 'linked' => false])
                        <h3 class="font-heading text-xl text-white font-bold mb-2 drop-shadow mt-3 transition-colors duration-150 group-hover:opacity-80">
                            {{ $featured->title }}
                        </h3>
                        @if ($featured->excerpt)
                        <p class="text-sm text-white/80 line-clamp-2 mb-3">{{ $featured->excerpt }}</p>
                        @endif
                        <div class="flex items-center gap-3 text-xs text-white/60">
                            <time>{{ $featured->published_at->translatedFormat('d M Y') }}</time>
                        </div>
                    </div>
                </div>
            </article>
        </a>

        {{-- Lista compacta --}}
        <div class="lg:col-span-2 flex flex-col gap-4">
            @foreach ($rest as $article)
            <a href="{{ $article->getUrl() }}" class="group block">
                <article class="flex gap-4 bg-bg-surface rounded-xl p-4 border border-border-subtle transition-all duration-200 hover:shadow-md"
                         onmouseenter="this.style.borderColor='{{ $section->accent_color ?? 'var(--color-accent)' }}66'"
                         onmouseleave="this.style.borderColor=''">
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                        @if ($article->featured_image)
                            <img src="{{ $article->getFeaturedImageUrl() }}"
                                 alt="{{ $article->featured_image_alt ?? $article->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full bg-bg-elevated flex items-center justify-center">
                                <svg class="w-6 h-6 text-text-low" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col justify-between min-w-0 flex-1">
                        <div>
                            @include('partials.category-badge', ['category' => $article->category, 'linked' => false])
                            <h4 class="text-sm font-medium text-text-high line-clamp-2 group-hover:text-accent transition-colors duration-150 leading-snug mt-1.5">
                                {{ $article->title }}
                            </h4>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-text-low mt-2">
                            <time>{{ $article->published_at->translatedFormat('d M Y') }}</time>
                        </div>
                    </div>
                </article>
            </a>
            @endforeach

            {{-- CTA dashed --}}
            @if ($section->show_view_all && $section->view_all_url)
            <a href="{{ $section->view_all_url }}"
               class="flex items-center justify-center py-3 rounded-xl border border-border-subtle border-dashed text-sm text-text-low hover:text-accent hover:border-accent/30 transition-all duration-200">
                Ver mais →
            </a>
            @endif
        </div>
    </div>
</section>
@endif
