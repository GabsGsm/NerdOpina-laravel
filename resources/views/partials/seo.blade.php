@php
    $siteTitle = config('blog.name');
    $pageTitle = $seo['title'] ?? $siteTitle;
    $fullTitle = ($pageTitle !== $siteTitle)
        ? "{$pageTitle} — {$siteTitle}"
        : $siteTitle;
@endphp

<title>{{ $fullTitle }}</title>

<meta name="description" content="{{ $seo['description'] ?? config('blog.description') }}">
<meta name="robots"      content="{{ $seo['robots'] ?? 'index,follow' }}">
<link rel="canonical"    href="{{ $seo['canonical'] ?? request()->url() }}">
<link rel="alternate"    type="application/rss+xml"
      title="{{ $siteTitle }}" href="{{ route('feed.rss') }}">

{{-- Open Graph --}}
<meta property="og:site_name"   content="{{ $siteTitle }}">
<meta property="og:locale"      content="pt_BR">
<meta property="og:type"        content="{{ $seo['og_type'] ?? 'website' }}">
<meta property="og:title"       content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $seo['description'] ?? config('blog.description') }}">
<meta property="og:url"         content="{{ $seo['canonical'] ?? request()->url() }}">
<meta property="og:image"       content="{{ $seo['og_image'] ?? asset(config('blog.og_image')) }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $seo['description'] ?? config('blog.description') }}">
<meta name="twitter:image"       content="{{ $seo['og_image'] ?? asset(config('blog.og_image')) }}">

{{-- Schema.org --}}
@if (!empty($seo['schema']))
<script type="application/ld+json">
{!! json_encode($seo['schema'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if (request()->routeIs('home'))
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    'name'     => config('blog.name'),
    'url'      => config('app.url'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif