@props(['post', 'compact' => false])

<a href="{{ $post->getUrl() }}" class="group block h-full">
    <article class="bg-bg-surface rounded-xl p-5 border border-border-subtle
                    hover:border-border-focus transition-all duration-200 hover:shadow-lg h-full flex flex-col">

        @include('partials.category-badge', ['category' => $post->category, 'linked' => false])

        <h3 class="font-heading font-bold text-text-high mt-2 mb-4
                   group-hover:text-accent transition-colors duration-150 leading-snug">
            {{ $post->title }}
        </h3>

        @php
            $items = $post->getTopListItems();
            $displayItems = $compact ? $items->take(3) : $items->take(5);
        @endphp

        <div class="space-y-2.5 flex-1">
            @foreach ($displayItems as $item)
            <div class="flex items-center gap-3 {{ $item->rank === 1 ? 'pb-3 border-b border-border-subtle' : '' }}">

                <span class="font-heading text-xl font-black text-accent shrink-0 w-7 text-center leading-none">
                    {{ $item->rank }}
                </span>

                @if ($item->getImageUrl())
                <img src="{{ $item->getImageUrl() }}"
                     alt="{{ $item->title }}"
                     class="w-9 h-9 rounded-lg object-cover shrink-0"
                     loading="lazy">
                @endif

                <span class="text-sm text-text-high font-medium truncate">
                    {{ $item->title }}
                </span>
            </div>
            @endforeach
        </div>

        @if (!$compact && $items->count() > 5)
        <p class="text-xs text-text-low mt-4 pt-3 border-t border-border-subtle">
            +{{ $items->count() - 5 }} itens na lista
        </p>
        @endif

    </article>
</a>
