<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->with('category')
            ->where('type', 'article')
            ->paginate(12);

        $topLists = Post::published()
            ->ofType('toplist')
            ->with(['category', 'topListItems'])
            ->limit(6)
            ->get();

        $latestNews = Post::published()
            ->with('category')
            ->where('type', 'article')
            ->limit(6)
            ->get();

        $popularPosts = Post::published()
            ->with('category')
            ->limit(5)
            ->get();

        $featuredTopList = $topLists->first();

        return view('posts.index', compact(
            'posts', 'topLists', 'latestNews',
            'popularPosts', 'featuredTopList'
        ));
    }

    public function show(string $slug)
    {
        $post = Post::published()
            ->with(['category', 'category.parent'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Carrega topListItems só se for do tipo toplist
        if ($post->isTopList()) {
            $post->load('topListItems');
        }

        $related = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with('category')
            ->limit(3)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }

    public function search(Request $request)
    {
        $query    = $request->get('q', '');
        $category = $request->get('category', '');

        $posts = Post::published()
            ->with('category')
            ->when($query, fn($q) => $q->where(fn($q) =>
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('excerpt', 'like', "%{$query}%")
            ))
            ->when($category, fn($q) => $q->whereHas('category', fn($q) =>
                $q->where('slug', $category)
            ))
            ->paginate(9)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->get();

        return view('search.index', compact('posts', 'query', 'categories'));
    }
}