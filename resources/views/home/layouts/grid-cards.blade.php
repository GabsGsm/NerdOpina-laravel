{{-- Layout: Grade Elevada — N cards em fundo elevado com decorações e badge TREND --}}

<section>
    <div class="relative bg-bg-elevated border border-border-subtle rounded-2xl overflow-hidden py-10 px-6 md:px-10">
        {{-- Orbs decorativos --}}
        <div class="absolute -top-12 -right-12 w-52 h-52 rounded-full blur-3xl opacity-[0.07] pointer-events-none"
             style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}"></div>
        <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full blur-3xl opacity-[0.06] pointer-events-none"
             style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}"></div>

        {{-- Cabeçalho --}}
        @if ($section->show_title || $section->show_view_all)
        <div class="flex items-center justify-between mb-8">
            @if ($section->show_title)
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 rounded-full"
                     style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}"></div>
                <h2 class="font-heading text-2xl md:text-3xl font-bold text-text-high">
                    {{ $section->title }}
                </h2>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border"
                      style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}33; color: {{ $section->accent_color ?? 'var(--color-accent)' }}; border-color: {{ $section->accent_color ?? 'var(--color-accent)' }}4d">
                    TREND
                </span>
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

        {{-- Grade de cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach ($section->posts as $index => $article)
            <a href="{{ $article->getUrl() }}" class="group block">
                <article class="flex flex-col h-full bg-bg-surface rounded-xl overflow-hidden border border-border-subtle transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5"
                         onmouseenter="this.style.borderColor='{{ $section->accent_color ?? 'var(--color-accent)' }}80'"
                         onmouseleave="this.style.borderColor=''">
                    {{-- Imagem --}}
                    <div class="relative aspect-video overflow-hidden">
                        @if ($article->featured_image)
                            <img src="{{ $article->getFeaturedImageUrl() }}"
                                 alt="{{ $article->featured_image_alt ?? $article->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full bg-bg-elevated flex items-center justify-center">
                                <svg class="w-12 h-12 text-text-low" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Shimmer overlay no hover --}}
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                             style="background: linear-gradient(135deg, transparent, {{ $section->accent_color ?? 'var(--color-accent)' }}33)">
                        </div>

                        {{-- Badge HOT no primeiro card --}}
                        @if ($loop->first)
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded"
                                  style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}; color: #000">
                                ★ HOT
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Conteúdo --}}
                    <div class="flex-1 flex flex-col p-4">
                        @include('partials.category-badge', ['category' => $article->category, 'linked' => false])
                        <h3 class="font-heading text-sm text-text-high mb-2 group-hover:text-accent transition-colors duration-150 line-clamp-2 leading-snug mt-2">
                            {{ $article->title }}
                        </h3>
                        @if ($article->excerpt)
                        <p class="text-xs text-text-medium line-clamp-2 mb-3 leading-relaxed">
                            {{ $article->excerpt }}
                        </p>
                        @endif
                        <div class="flex items-center gap-3 text-xs text-text-low">
                            <time>{{ $article->published_at->translatedFormat('d M Y') }}</time>
                        </div>
                    </div>
                </article>
            </a>
            @endforeach
        </div>
    </div>
</section>
