@props(['post'])

<article class="flex flex-col gap-3 bg-gray-900 rounded-xl overflow-hidden hover:ring-1 hover:ring-gray-700 transition">
    @if ($post->featured_image)
    <img src="{{ asset($post->featured_image) }}"
         alt="{{ $post->featured_image_alt }}"
         class="w-full h-48 object-cover"
         loading="lazy" decoding="async">
    @endif

    <div class="p-4 flex flex-col gap-2">
        @include('partials.category-badge', ['category' => $post->category])

        <h2 class="font-bold text-lg leading-snug">
            <a href="{{ $post->getUrl() }}" class="hover:text-indigo-400 transition">
                {{ $post->title }}
            </a>
        </h2>

        <p class="text-gray-400 text-sm line-clamp-3">{{ $post->excerpt }}</p>

        <time class="text-xs text-gray-500" datetime="{{ $post->published_at->toIso8601String() }}">
            {{ $post->published_at->format('d/m/Y') }}
        </time>
    </div>
</article>