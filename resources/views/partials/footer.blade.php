<footer class="bg-bg-header-footer border-t border-border-subtle mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- Logo e tagline --}}
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <x-logo />
                </a>
                <p class="mt-4 text-sm text-text-medium max-w-xs leading-relaxed">
                    Seu portal de cultura nerd com reviews, notícias e análises sobre games, animes, tech e muito mais.
                </p>
                <div class="flex gap-3 mt-6">
                    <a href="#" class="p-2 text-text-medium hover:text-accent hover:bg-bg-elevated rounded-lg
                                      transition-all duration-150" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 text-text-medium hover:text-accent hover:bg-bg-elevated rounded-lg
                                      transition-all duration-150" aria-label="Twitter">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 text-text-medium hover:text-accent hover:bg-bg-elevated rounded-lg
                                      transition-all duration-150" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke-width="2"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"/>
                        </svg>
                    </a>
                    <a href="{{ route('feed.rss') }}" class="p-2 text-text-medium hover:text-accent hover:bg-bg-elevated rounded-lg
                                      transition-all duration-150" aria-label="RSS">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.18 15.64a2.18 2.18 0 012.18 2.18C8.36 19.01 7.38 20 6.18 20C4.98 20 4 19.01 4 17.82a2.18 2.18 0 012.18-2.18M4 4.44A15.56 15.56 0 0119.56 20h-2.83A12.73 12.73 0 004 7.27V4.44m0 5.66a9.9 9.9 0 019.9 9.9h-2.83A7.07 7.07 0 004 12.93V10.1z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Categorias (dinâmico do banco) --}}
            <div>
                <h3 class="font-heading font-bold text-text-high mb-4 text-sm uppercase tracking-wide">
                    Categorias
                </h3>
                <ul class="space-y-2.5">
                    @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="text-sm text-text-medium hover:text-accent transition-colors duration-150">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Páginas (dinâmico do banco) --}}
            <div>
                <h3 class="font-heading font-bold text-text-high mb-4 text-sm uppercase tracking-wide">
                    Páginas
                </h3>
                <ul class="space-y-2.5">
                    @foreach ($footerPages as $page)
                    <li>
                        <a href="{{ $page->getUrl() }}"
                           class="text-sm text-text-medium hover:text-accent transition-colors duration-150">
                            {{ $page->title }}
                        </a>
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ route('sitemap') }}"
                           class="text-sm text-text-medium hover:text-accent transition-colors duration-150">
                            Sitemap
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="mt-12 pt-8 border-t border-border-subtle flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-text-low">
                © {{ date('Y') }} NerdOpina. Todos os direitos reservados.
            </p>
            <div class="flex items-center gap-4 text-xs text-text-low">
                <a href="{{ route('feed.rss') }}" class="hover:text-text-medium transition-colors duration-150">RSS Feed</a>
            </div>
        </div>
    </div>
</footer>
