<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->select('slug', 'updated_at')
            ->get();

        $categories = Category::select('slug', 'updated_at')->get();

        $staticPages = [
            ['url' => route('home'),          'priority' => '1.0', 'freq' => 'daily',   'updated' => now()],
            ['url' => route('pages.sobre'),   'priority' => '0.8', 'freq' => 'monthly', 'updated' => now()],
            ['url' => route('pages.contato'), 'priority' => '0.6', 'freq' => 'monthly', 'updated' => now()],
        ];

        $content = view('sitemap.index', compact('posts', 'categories', 'staticPages'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}