@props(['post', 'compact' => false])

<a href="{{ $post->getUrl() }}" class="group block">
    <article class="bg-bg-surface rounded-lg p-5 border border-border-subtle
                    hover:border-border-focus transition-all duration-200 hover:shadow-lg h-full flex flex-col">

        @include('partials.category-badge', ['category' => $post->category])

        <h3 class="font-bold text-text-high mt-2 mb-4 group-hover:text-accent transition-colors duration-150"
            style="font-family: var(--font-heading)">
            {{ $post->title }}
        </h3>

        @php
            $items = $post->getTopListItems();
            $displayItems = $compact ? $items->take(3) : $items->take(5);
        @endphp

        <div class="space-y-3 flex-1">
            @foreach ($displayItems as $item)
            <div class="flex items-center gap-3 {{ $item->rank === 1 ? 'pb-3 border-b border-border-subtle' : '' }}">
                <span class="text-2xl font-bold text-accent flex-shrink-0 w-8"
                      style="font-family: var(--font-heading)">
                    {{ $item->rank }}
                </span>

                @if ($item->image_url)
                <img src="{{ $item->image_url }}"
                     alt="{{ $item->title }}"
                     class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                     loading="lazy">
                @endif

                <span class="text-sm text-text-high font-medium truncate">
                    {{ $item->title }}
                </span>
            </div>
            @endforeach
        </div>

        @if (!$compact && $items->count() > 5)
        <p class="text-xs text-text-low mt-4">
            +{{ $items->count() - 5 }} mais
        </p>
        @endif
    </article>
</a>