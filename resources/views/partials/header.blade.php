<header
    x-data="headerManager()"
    @scroll.window="onScroll()"
    :class="isScrolled ? 'bg-bg-header-footer/95 backdrop-blur-md' : 'bg-bg-header-footer'"
    class="sticky top-0 z-50 border-b border-border-subtle transition-all duration-200">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
               <x-logo />
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-6">
                <div>
                    <a href="{{ route('home') }}"class="flex items-center gap-1 text-sm text-text-medium hover:text-text-high transition-colors duration-150 relative group">
                        Home                       
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-accent group-hover:w-full transition-all duration-200"></span>
                    </a>
                </div>
                @foreach ($categories as $category)

                    @if ($category->posts_count > 0 || $category->children->isNotEmpty())
                    <div class="relative group"
                        x-data="{ open: false }"
                        @mouseenter="open = true"
                        @mouseleave="open = false">

                        <a href="{{ route('categories.show', $category->slug) }}"
                        class="flex items-center gap-1 text-sm text-text-medium hover:text-text-high transition-colors duration-150 relative group">
                        
                            {{ $category->name }}

                            @if ($category->children->isNotEmpty())
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"/>
                                </svg>
                            @endif

                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-accent group-hover:w-full transition-all duration-200"></span>
                        </a>

                        {{-- Dropdown --}}
                        @if ($category->children->isNotEmpty())
                            <div x-show="open" x-transition x-cloak
                                class="absolute top-full left-0 pt-2 min-w-45 z-50">
                                <div class="bg-bg-surface border border-border-subtle rounded-lg shadow-lg py-2">

                                    @foreach ($category->children as $child)                                       
                                        <a href="{{ route('categories.show', $child->slug) }}"
                                        class="block px-4 py-2 text-sm text-text-medium hover:text-text-high hover:bg-bg-elevated transition-colors duration-150">
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
            <div class="flex items-center gap-3">

                {{-- Search --}}
                <div x-data="{ open: false, query: '' }">
                    <button x-show="!open"  @click="open = true"
                            class="p-2 text-text-medium hover:text-text-high transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="flex items-center gap-2">
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
                    <svg x-show="!isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Mobile menu toggle --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="lg:hidden p-2 text-text-medium hover:text-text-high transition-colors duration-150">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Nav --}}
        <nav x-show="mobileOpen" x-cloak
            x-transition
            class="lg:hidden py-4 border-t border-border-subtle">
            <div class="mb-1">
                <a href="{{ route('home') }}"class="text-text-medium hover:text-text-high transition-colors duration-150">
                    Home
                </a>
            </div>

            @foreach ($categories as $category)

                @if ($category->posts_count > 0 || $category->children->isNotEmpty())

                <div x-data="{ subOpen: false }" class="mb-1">

                    <div class="flex items-center justify-between py-2">

                        {{-- Link principal --}}
                        <a href="{{ route('categories.show', $category->slug) }}"
                        class="text-text-medium hover:text-text-high transition-colors duration-150">
                            {{ $category->name }}
                        </a>

                        {{-- Botão dropdown --}}
                        @if ($category->children->isNotEmpty())
                        <button @click="subOpen = !subOpen"
                                class="p-1 text-text-medium">

                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="subOpen ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"/>
                            </svg>

                        </button>
                        @endif
                    </div>

                    {{-- Subcategorias --}}
                    @if ($category->children->isNotEmpty())
                        <div x-show="subOpen"
                            x-collapse
                            class="ml-4 mt-1 space-y-1">

                            @foreach ($category->children as $child)                                
                                <a href="{{ route('categories.show', $child->slug) }}"
                                class="block py-1.5 text-sm text-text-low hover:text-text-medium transition-colors duration-150">
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