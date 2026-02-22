<!DOCTYPE html>
<html lang="pt-BR"
      x-data="themeManager()"
      x-init="init()"
      :class="{ 'dark': isDark }"
      class="dark min-h-screen w-full overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.seo')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-bg-base text-text-high min-h-screen w-full flex flex-col relative overflow-x-hidden">

    {{-- Orbs decorativos --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none" style="z-index:0">
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-accent rounded-full blur-3xl"
            style="opacity: var(--orb-1-opacity); transition: opacity 0.3s ease"></div>
        <div class="absolute top-20 -right-20 w-60 h-60 bg-cat-reviews rounded-full blur-3xl"
            style="opacity: var(--orb-2-opacity); transition: opacity 0.3s ease"></div>
        <div class="absolute top-1/2 -left-32 w-96 h-96 bg-cat-guias rounded-full blur-3xl"
            style="opacity: var(--orb-3-opacity); transition: opacity 0.3s ease"></div>
        <div class="absolute bottom-40 -right-40 w-72 h-72 bg-cat-top-listas rounded-full blur-3xl"
            style="opacity: var(--orb-4-opacity); transition: opacity 0.3s ease"></div>
    </div>

    <div class="relative flex flex-col min-h-screen w-full" style="z-index:1">
        @include('partials.header')

        <main class="flex-1 w-full">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    <script>
        function themeManager() {
            return {
                isDark: true,
                init() {
                    const saved = localStorage.getItem('theme');
                    this.isDark = saved ? saved === 'dark' : true;
                },
                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                }
            }
        }
    </script>
</body>
</html>