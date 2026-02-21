@props(['category'])

@php
$colorMap = [
    'reviews'    => 'text-cat-reviews',
    'top-listas' => 'text-cat-top-listas',
    'guias'      => 'text-cat-guias',
    'tech'       => 'text-cat-tech',
    'noticias'   => 'text-cat-noticias',
    'anime'      => 'text-cat-anime',
    'kpop'       => 'text-cat-kpop',
    'games'      => 'text-cat-games',
];
$colorClass = $colorMap[strtolower($category->slug)] ?? null;
@endphp

<a href="{{ $category->getUrl() }}"
   class="inline-block text-xs font-medium uppercase tracking-wide transition-colors duration-150 hover:opacity-80
          {{ $colorClass ?? '' }}"
   @if (!$colorClass) style="color: {{ $category->color }}" @endif>
    {{ $category->name }}
</a>