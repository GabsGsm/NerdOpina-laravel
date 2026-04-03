@switch($widget->type)
    @case('popular_posts')
        @include('home.widgets.popular-posts', compact('widget'))
        @break
    @case('featured_toplist')
        @include('home.widgets.featured-toplist', compact('widget'))
        @break
    @default
        @include('home.widgets.categories', compact('widget'))
@endswitch
