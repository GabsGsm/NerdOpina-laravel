@props(['category', 'linked' => true])

@php
$colorMap = [
    'reviews'    => ['text' => 'text-cat-reviews',    'bg' => 'bg-cat-reviews/15'],
    'top-listas' => ['text' => 'text-cat-top-listas', 'bg' => 'bg-cat-top-listas/15'],
    'guias'      => ['text' => 'text-cat-guias',      'bg' => 'bg-cat-guias/15'],
    'tech'       => ['text' => 'text-cat-tech',       'bg' => 'bg-cat-tech/15'],
    'noticias'   => ['text' => 'text-cat-noticias',   'bg' => 'bg-cat-noticias/15'],
    'anime'      => ['text' => 'text-cat-anime',      'bg' => 'bg-cat-anime/15'],
    'kpop'       => ['text' => 'text-cat-kpop',       'bg' => 'bg-cat-kpop/15'],
    'games'      => ['text' => 'text-cat-games',      'bg' => 'bg-cat-games/15'],
];
$colors = $colorMap[strtolower($category->slug)] ?? null;
$badgeClass = 'inline-flex w-fit items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wider ' . ($colors ? $colors['text'] . ' ' . $colors['bg'] : '');
$badgeStyle = $colors ? '' : 'color: ' . $category->color . '; background-color: ' . $category->color . '26;';
@endphp

@if ($linked)
<a href="{{ $category->getUrl() }}"
   class="{{ $badgeClass }} transition-opacity duration-150 hover:opacity-75"
   @if ($badgeStyle) style="{{ $badgeStyle }}" @endif>
    {{ $category->name }}
</a>
@else
<span class="{{ $badgeClass }}"
      @if ($badgeStyle) style="{{ $badgeStyle }}" @endif>
    {{ $category->name }}
</span>
@endif
