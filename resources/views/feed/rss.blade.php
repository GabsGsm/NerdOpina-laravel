<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title>{{ config('blog.name') }}</title>
    <link>{{ config('app.url') }}</link>
    <description>{{ config('blog.description') }}</description>
    <language>pt-BR</language>
    <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
    <atom:link href="{{ route('feed.rss') }}" rel="self" type="application/rss+xml"/>
    @foreach ($posts as $post)
    <item>
        <title><![CDATA[{{ $post->title }}]]></title>
        <link>{{ $post->getUrl() }}</link>
        <description><![CDATA[{{ $post->excerpt }}]]></description>
        <content:encoded><![CDATA[{!! $post->content !!}]]></content:encoded>
        <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
        <guid isPermaLink="true">{{ $post->getUrl() }}</guid>
        <category>{{ $post->category->name }}</category>
    </item>
    @endforeach
</channel>
</rss>