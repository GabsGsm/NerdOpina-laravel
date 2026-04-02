<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

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

    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->with('parent', 'children')
            ->firstOrFail();

        // Inclui posts das subcategorias automaticamente
        $categoryIds = $category->allChildrenIds();

        $posts = Post::published()
            ->whereIn('category_id', $categoryIds)
            ->with('category')
            ->paginate(12);

        // TopLists da categoria em destaque
        $topLists = Post::published()
            ->ofType('toplist')
            ->whereIn('category_id', $categoryIds)
            ->with(['category', 'topListItems'])
            ->limit(4)
            ->get();

        return view('categories.show', compact('category', 'posts', 'topLists'));
    }
}
