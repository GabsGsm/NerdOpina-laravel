<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomeContainer;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $containers = HomeContainer::active()
            ->orderBy('position')
            ->with([
                'sections' => fn ($q) => $q->active()->orderBy('position'),
                'sidebarWidgets' => fn ($q) => $q->where('is_active', true)->orderBy('position'),
            ])
            ->get();

        foreach ($containers as $container) {
            foreach ($container->sections as $section) {
                $section->setRelation('posts', $section->buildPostQuery()->get());
            }
        }

        $featuredPost = Post::published()
            ->with('category')
            ->latest('published_at')
            ->first();

        return view('posts.index', compact('containers', 'featuredPost'));
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
