<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function sobre()
    {
        $page = Page::where('slug', 'sobre')->firstOrFail();
        return view('pages.sobre', compact('page'));
    }

    public function contato()
    {
        $page = Page::where('slug', 'contato')->firstOrFail();
        return view('pages.contato', compact('page'));
    }
}