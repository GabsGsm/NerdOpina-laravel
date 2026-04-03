<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function toplist(): \Illuminate\View\View
    {
        $featured = Post::published()
            ->ofType('toplist')
            ->with(['category', 'topListItems'])
            ->latest()
            ->limit(3)
            ->get();

        $posts = Post::published()
            ->ofType('toplist')
            ->with(['category', 'topListItems'])
            ->paginate(12);

        return view('toplists.index', compact('featured', 'posts'));
    }

    public function show(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->with('parent', 'children')
            ->firstOrFail();

        $categoryIds = $category->allChildrenIds();

        // Filtro de subcategoria
        if ($request->filled('subcategory')) {
            $sub = $category->children->firstWhere('slug', $request->subcategory);
            if ($sub) {
                $categoryIds = $sub->allChildrenIds();
            }
        }

        $query = Post::published()
            ->whereIn('category_id', $categoryIds)
            ->with('category');

        // Busca por título
        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->q.'%');
        }

        // Ordenação
        $query->reorder();
        match ($request->get('sort', 'recent')) {
            'oldest' => $query->orderBy('published_at'),
            'title-asc' => $query->orderBy('title'),
            'title-desc' => $query->orderByDesc('title'),
            default => $query->orderByDesc('published_at'),
        };

        $posts = $query->paginate(12)->withQueryString();

        $topLists = Post::published()
            ->ofType('toplist')
            ->whereIn('category_id', $category->allChildrenIds())
            ->with(['category', 'topListItems'])
            ->limit(4)
            ->get();

        return view('categories.show', compact('category', 'posts', 'topLists'));
    }
}
