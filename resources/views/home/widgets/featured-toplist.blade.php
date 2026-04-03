@php
    $widgetToplist = \App\Models\Post::published()
        ->ofType('toplist')
        ->with('topListItems')
        ->latest('published_at')
        ->first();
@endphp

@if ($widgetToplist)
    @include('partials.toplist-card', ['post' => $widgetToplist, 'compact' => true])
@endif
