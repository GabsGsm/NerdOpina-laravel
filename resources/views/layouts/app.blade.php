<!DOCTYPE html>
<html lang="pt-BR"
      x-data="themeManager()"
      x-init="init()"
      :class="{ 'dark': isDark }"
      class="min-h-screen w-full overflow-x-hidden">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-709P1SH8VS"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-709P1SH8VS');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Previne FOUC: aplica tema antes do Alpine inicializar --}}
    <script>
        (function () {
            var saved = localStorage.getItem('theme');
            if (saved === 'dark' || !saved) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @include('partials.seo')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script async custom-element="amp-auto-ads"
            src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js"></script>
</head>
<body class="antialiased bg-bg-base text-text-high min-h-screen w-full flex flex-col relative overflow-x-hidden">

<amp-auto-ads type="adsense"
              data-ad-client="ca-pub-4333793854754492"></amp-auto-ads>

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
        <div class="h-16 shrink-0"></div>

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