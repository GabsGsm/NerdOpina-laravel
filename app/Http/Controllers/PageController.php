<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function sobre(): View
    {
        $page = Page::where('slug', 'sobre')->firstOrFail();

        return view('pages.sobre', compact('page'));
    }

    public function contato(): View
    {
        $page = Page::where('slug', 'contato')->firstOrFail();

        return view('pages.contato', compact('page'));
    }

    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
