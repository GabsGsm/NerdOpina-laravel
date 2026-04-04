{{-- Layout: Editorial Destaque — 1 post com imagem 3/5 + conteúdo 2/5 --}}
@php $post = $section->posts->first(); @endphp
@if ($post)
<section>
    {{-- Cabeçalho --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border"
             style="background-color: {{ $section->accent_color ?? 'var(--color-accent)' }}1a; border-color: {{ $section->accent_color ?? 'var(--color-accent)' }}4d">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"
                 style="color: {{ $section->accent_color ?? 'var(--color-accent)' }}">
                <path d="M12 2l1.5 4.5H18l-3.75 2.75 1.5 4.5L12 11.25 8.25 13.75l1.5-4.5L6 6.5h4.5L12 2z"/>
                <path d="M5 17l.75 2.25H8L6.13 20.5l.75 2.25L5 21.5l-1.88 1.25.75-2.25L2 18.75h2.25L5 16.5V17z" opacity=".6"/>
                <path d="M19 17l.75 2.25H22l-1.88 1.25.75 2.25L19 21.5l-1.88 1.25.75-2.25L16 18.75h2.25L19 16.5V17z" opacity=".6"/>
            </svg>
            <span class="text-xs font-medium tracking-wider uppercase"
                  style="color: {{ $section->accent_color ?? 'var(--color-accent)' }}">
                {{ $section->title }}
            </span>
        </div>
        <div class="h-px flex-1 bg-border-subtle"></div>
        <span class="text-xs text-text-low">
            {{ $post->published_at->translatedFormat('d M Y') }}
        </span>
    </div>

    {{-- Card editorial --}}
    <a href="{{ $post->getUrl() }}" class="group block">
        <div class="relative bg-bg-surface border border-border-subtle rounded-xl overflow-hidden hover:border-border-focus transition-all duration-300 hover:shadow-xl">
            {{-- Faixa accent esquerda --}}
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl"
                 style="background: linear-gradient(to bottom, {{ $section->accent_color ?? 'var(--color-accent)' }}, {{ $section->accent_color ?? 'var(--color-accent)' }}99, transparent)">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 min-h-[300px]">
                {{-- Imagem — 3/5 no desktop --}}
                <div class="md:col-span-3 relative overflow-hidden h-64 md:h-auto">
                    @if ($post->featured_image)
                        <img src="{{ $post->getFeaturedImageUrl() }}"
                             alt="{{ $post->featured_image_alt ?? $post->title }}"
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
                    {{-- Fade lateral para o conteúdo no desktop --}}
                    <div class="hidden md:block absolute inset-y-0 right-0 w-24 bg-gradient-to-r from-transparent to-bg-surface"></div>
                    {{-- Fade inferior no mobile --}}
                    <div class="md:hidden absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-bg-surface to-transparent"></div>
                </div>

                {{-- Conteúdo — 2/5 no desktop --}}
                <div class="md:col-span-2 flex flex-col justify-center p-7 md:pl-4">
                    @include('partials.category-badge', ['category' => $post->category, 'linked' => false])

                    <h2 class="font-heading text-text-high mb-3 group-hover:text-accent transition-colors duration-150 text-2xl md:text-3xl leading-tight mt-4">
                        {{ $post->title }}
                    </h2>

                    @if ($post->excerpt)
                    <p class="text-sm text-text-medium mb-5 line-clamp-3 leading-relaxed">
                        {{ $post->excerpt }}
                    </p>
                    @endif

                    {{-- CTA --}}
                    <div class="inline-flex items-center gap-2 text-sm font-medium group-hover:gap-3 transition-all duration-150"
                         style="color: {{ $section->accent_color ?? 'var(--color-accent)' }}">
                        Ler artigo completo
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </a>
</section>
@endif
