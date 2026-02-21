@extends('layouts.app')

@php
$seo = [
    'title'       => $page->getSeoTitle(),
    'description' => $page->getSeoDescription(),
    'canonical'   => route('pages.sobre'),
];
@endphp

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <header class="mb-12">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-1.5 h-10 bg-accent rounded-full"></div>
            <h1 class="text-3xl md:text-4xl font-bold text-text-high"
                style="font-family: var(--font-heading)">
                {{ $page->title }}
            </h1>
        </div>
    </header>

    <div class="prose prose-lg max-w-none
                prose-headings:font-bold prose-headings:text-text-high
                prose-p:text-text-medium prose-p:leading-relaxed
                prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                prose-strong:text-text-high">
        {!! $page->content !!}
    </div>

</div>
@endsection