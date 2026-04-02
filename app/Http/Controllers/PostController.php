<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $sections = HomeSection::active()
            ->orderBy('position')
            ->get()
            ->map(function (HomeSection $section): HomeSection {
                $section->posts = $section->buildPostQuery()->get();

                return $section;
            });

        $popularPosts = Post::published()
            ->with('category')
            ->limit(5)
            ->get();

        $featuredPost = Post::published()
            ->with('category')
            ->latest('published_at')
            ->first();

        return view('posts.index', compact('sections', 'popularPosts', 'featuredPost'));
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
        $query = $request->get('q', '');
        $category = $request->get('category', '');

        $posts = Post::published()
            ->with('category')
            ->when($query, fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', "%{$query}%")
                ->orWhere('excerpt', 'like', "%{$query}%")
            ))
            ->when($category, fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $category)
            ))
            ->paginate(9)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->get();

        return view('search.index', compact('posts', 'query', 'categories'));
    }
}
