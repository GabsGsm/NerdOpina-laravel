<header
    x-data="headerManager()"
    @scroll.window="onScroll()"
    :class="isScrolled ? 'bg-bg-header-footer/95 backdrop-blur-md' : 'bg-bg-header-footer'"
    class="sticky top-0 z-50 border-b border-border-subtle transition-all duration-200">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" class="text-accent">
                    <path d="M8 10C8 10 10 4 12 4C13 4 14 6 14 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M28 10C28 10 26 4 24 4C23 4 22 6 22 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="18" cy="18" r="11" stroke="currentColor" stroke-width="2"/>
                    <circle cx="13" cy="17" r="3.5" stroke="currentColor" stroke-width="2"/>
                    <circle cx="23" cy="17" r="3.5" stroke="currentColor" stroke-width="2"/>
                    <path d="M16.5 17H19.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M14 23C14 23 16 25 18 25C20 25 22 23 22 23" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-xl font-bold tracking-tight text-text-high" style="font-family: var(--font-heading)">NerdOpina</span>
            </a>

            {{-- Desktop Nav --}}
            @php
            $navLinks = [
                ['href' => route('home'), 'label' => 'Home', 'subs' => []],
                ['href' => route('categories.show', 'reviews'), 'label' => 'Reviews', 'subs' => [
                    ['href' => route('categories.show', 'games'), 'label' => 'Games'],
                    ['href' => route('categories.show', 'anime'), 'label' => 'Animes'],
                    ['href' => route('categories.show', 'tech'), 'label' => 'Tech'],
                ]],
                ['href' => route('categories.show', 'guias'), 'label' => 'Guias', 'subs' => [
                    ['href' => route('categories.show', 'games'), 'label' => 'Games'],
                    ['href' => route('categories.show', 'tecnologia'), 'label' => 'Tecnologia'],
                ]],
                ['href' => route('categories.show', 'top-listas'), 'label' => 'Top Listas', 'subs' => [
                    ['href' => route('categories.show', 'games'), 'label' => 'Games'],
                    ['href' => route('categories.show', 'anime'), 'label' => 'Animes'],
                    ['href' => route('categories.show', 'tech'), 'label' => 'Tech'],
                ]],
                ['href' => route('categories.show', 'noticias'), 'label' => 'Notícias', 'subs' => []],
                ['href' => route('categories.show', 'anime'), 'label' => 'Anime', 'subs' => []],
                ['href' => route('categories.show', 'tech'), 'label' => 'Tech', 'subs' => []],
            ];
            @endphp

            <nav class="hidden lg:flex items-center gap-6">
                @foreach ($navLinks as $link)
                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ $link['href'] }}"
                       class="flex items-center gap-1 text-sm text-text-medium hover:text-text-high transition-colors duration-150 relative group">
                        {{ $link['label'] }}
                        @if (!empty($link['subs']))
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        @endif
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-accent group-hover:w-full transition-all duration-200"></span>
                    </a>

                    @if (!empty($link['subs']))
                    <div x-show="open" x-transition
                         class="absolute top-full left-0 pt-2 min-w-[180px] z-50">
                        <div class="bg-bg-surface border border-border-subtle rounded-lg shadow-lg py-2">
                            @foreach ($link['subs'] as $sub)
                            <a href="{{ $sub['href'] }}"
                               class="block px-4 py-2 text-sm text-text-medium hover:text-text-high hover:bg-bg-elevated transition-colors duration-150">
                                {{ $sub['label'] }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-3">

                {{-- Search --}}
                <div x-data="{ open: false, query: '' }">
                    <button x-show="!open" @click="open = true"
                            class="p-2 text-text-medium hover:text-text-high transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <div x-show="open" class="flex items-center gap-2">
                        <form action="{{ route('search') }}" method="GET" class="flex items-center gap-2">
                            <input type="text" name="q" x-model="query" x-ref="searchInput"
                                   x-init="$watch('open', v => v && $nextTick(() => $refs.searchInput.focus()))"
                                   placeholder="Buscar..."
                                   class="w-48 px-3 py-1.5 bg-bg-elevated border border-border-subtle rounded-md text-sm text-text-high placeholder:text-text-low focus:outline-none focus:border-border-focus transition-colors duration-150">
                        </form>
                        <button @click="open = false; query = ''"
                                class="p-2 text-text-medium hover:text-text-high transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Dark mode toggle --}}
                <button @click="toggle()"
                        class="p-2 text-text-medium hover:text-text-high transition-colors duration-150">
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Mobile menu toggle --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="lg:hidden p-2 text-text-medium hover:text-text-high transition-colors duration-150">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Nav --}}
        <nav x-show="mobileOpen" x-transition class="lg:hidden py-4 border-t border-border-subtle">
            @foreach ($navLinks as $link)
            <div x-data="{ subOpen: false }" class="mb-1">
                <div class="flex items-center justify-between py-2">
                    <a href="{{ $link['href'] }}" class="text-text-medium hover:text-text-high transition-colors duration-150">
                        {{ $link['label'] }}
                    </a>
                    @if (!empty($link['subs']))
                    <button @click="subOpen = !subOpen" class="p-1 text-text-medium">
                        <svg class="w-4 h-4 transition-transform duration-200" :class="subOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @endif
                </div>
                @if (!empty($link['subs']))
                <div x-show="subOpen" class="ml-4 mt-1 space-y-1">
                    @foreach ($link['subs'] as $sub)
                    <a href="{{ $sub['href'] }}"
                       class="block py-1.5 text-sm text-text-low hover:text-text-medium transition-colors duration-150">
                        {{ $sub['label'] }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </nav>
    </div>
</header>

<script>
function headerManager() {
    return {
        isScrolled: false,
        mobileOpen: false,
        onScroll() {
            this.isScrolled = window.scrollY > 20;
        }
    }
}
</script>