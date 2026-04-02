<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_pages' => Page::count(),
        ];

        $recentPosts = Post::with('category')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentPosts'));
    }
}
