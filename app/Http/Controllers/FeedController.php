<?php

namespace App\Http\Controllers;

use App\Models\Post;

class FeedController extends Controller
{
    public function rss()
    {
        $posts = Post::published()
            ->with('category')
            ->limit(20)
            ->get();

        $content = view('feed.rss', compact('posts'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }
}