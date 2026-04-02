<header
    x-data="headerManager()"
    @scroll.window="onScroll()"
    :class="{
        'bg-bg-header-footer/60 backdrop-blur-xl shadow-sm': isScrolled,
        'bg-bg-header-footer': !isScrolled,
        '-translate-y-full': isHidden,
        'translate-y-0': !isHidden
    }"
    class="fixed top-0 left-0 right-0 z-50 border-b border-border-subtle transition-all duration-300">

    <div class="site-container">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
               <x-logo />
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}"
                   class="px-3 py-2 text-sm text-text-medium hover:text-text-high hover:bg-bg-elevated
                          rounded-lg transition-all duration-150">
                    Home
                </a>

                <a href="{{ route('toplists.index') }}"
                   class="px-3 py-2 text-sm text-text-medium hover:text-text-high hover:bg-bg-elevated
                          rounded-lg transition-all duration-150">
                    Top Listas
                </a>

                @foreach ($categories as $category)
                    @if ($category->posts_count > 0 || $category->children->isNotEmpty())
                    <div class="relative"
                        x-data="{ open: false }"
                        @mouseenter="open = true"
                        @mouseleave="open = false">

                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="flex items-center gap-1 px-3 py-2 text-sm text-text-medium
                                  hover:text-text-high hover:bg-bg-elevated rounded-lg transition-all duration-150">
                            {{ $category->name }}
                            @if ($category->children->isNotEmpty())
                                <svg class="w-3 h-3 transition-transform duration-150"
                                     :class="open ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            @endif
                        </a>

                        {{-- Dropdown --}}
                        @if ($category->children->isNotEmpty())
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 x-cloak
                                 class="absolute top-full left-0 pt-1.5 min-w-44 z-50">
                                <div class="bg-bg-surface border border-border-subtle rounded-xl shadow-lg py-1.5">
                                    @foreach ($category->children as $child)
                                        <a href="{{ route('categories.show', $child->slug) }}"
                                           class="block px-4 py-2 text-sm text-text-medium
                                                  hover:text-text-high hover:bg-bg-elevated
                                                  transition-colors duration-150">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                    @endif
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-1">

                {{-- Search --}}
                <div x-data="{ open: false, query: '' }">
                    <button x-show="!open" @click="open = true"
                            class="p-2.5 text-text-medium hover:text-text-high hover:bg-bg-elevated
                                   rounded-lg transition-all duration-150">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="flex items-center gap-1">
                        <form action="{{ route('search') }}" method="GET" class="flex items-center gap-1">
                            <input type="text" name="q" x-model="query" x-ref="searchInput"
                                   x-init="$watch('open', v => v && $nextTick(() => $refs.searchInput.focus()))"
                                   placeholder="Buscar..."
                                   class="w-44 sm:w-52 px-3 py-1.5 bg-bg-elevated border border-border-subtle
                                          rounded-lg text-sm text-text-high placeholder:text-text-low
                                          focus:outline-none focus:border-border-focus transition-colors duration-150">
                        </form>
                        <button @click="open = false; query = ''"
                                class="p-2.5 text-text-medium hover:text-text-high hover:bg-bg-elevated
                                       rounded-lg transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Dark mode toggle --}}
                <button @click="toggle()"
                        class="p-2.5 text-text-medium hover:text-text-high hover:bg-bg-elevated
                               rounded-lg transition-all duration-150"
                        :aria-label="isDark ? 'Mudar para modo claro' : 'Mudar para modo escuro'">
                    <svg x-show="isDark" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!isDark" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Mobile menu toggle --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="lg:hidden p-2.5 text-text-medium hover:text-text-high hover:bg-bg-elevated
                               rounded-lg transition-all duration-150">
                    <svg x-show="!mobileOpen" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Nav --}}
        <nav x-show="mobileOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden py-3 border-t border-border-subtle">

            <a href="{{ route('home') }}"
               class="block px-3 py-2.5 text-sm text-text-medium hover:text-text-high
                      hover:bg-bg-elevated rounded-lg transition-colors duration-150 mb-1">
                Home
            </a>

            <a href="{{ route('toplists.index') }}"
               class="block px-3 py-2.5 text-sm text-text-medium hover:text-text-high
                      hover:bg-bg-elevated rounded-lg transition-colors duration-150 mb-1">
                Top Listas
            </a>

            @foreach ($categories as $category)
                @if ($category->posts_count > 0 || $category->children->isNotEmpty())

                <div x-data="{ subOpen: false }">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="flex-1 px-3 py-2.5 text-sm text-text-medium hover:text-text-high
                                  hover:bg-bg-elevated rounded-lg transition-colors duration-150">
                            {{ $category->name }}
                        </a>
                        @if ($category->children->isNotEmpty())
                        <button @click="subOpen = !subOpen"
                                class="p-2.5 text-text-medium hover:text-text-high hover:bg-bg-elevated
                                       rounded-lg transition-colors duration-150">
                            <svg class="w-4 h-4 transition-transform duration-150"
                                 :class="subOpen ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        @endif
                    </div>

                    @if ($category->children->isNotEmpty())
                        <div x-show="subOpen"
                             x-collapse
                             class="ml-3 mt-1 space-y-0.5 pl-3 border-l border-border-subtle">
                            @foreach ($category->children as $child)
                                <a href="{{ route('categories.show', $child->slug) }}"
                                   class="block px-3 py-2 text-sm text-text-low hover:text-text-medium
                                          hover:bg-bg-elevated rounded-lg transition-colors duration-150">
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @endif
            @endforeach

        </nav>
    </div>

    @stack('header-bottom')
</header>

<script>
function headerManager() {
    return {
        isScrolled: false,
        isHidden: false,
        mobileOpen: false,
        lastScrollY: 0,
        onScroll() {
            const currentY = window.scrollY;
            const scrollingDown = currentY > this.lastScrollY;

            this.isScrolled = currentY > 20;

            if (scrollingDown && currentY > 80) {
                this.isHidden = true;
                this.mobileOpen = false;
            } else {
                this.isHidden = false;
            }

            this.lastScrollY = currentY;
        }
    }
}
</script>
