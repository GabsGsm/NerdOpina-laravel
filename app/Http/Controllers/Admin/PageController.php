<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Traits\SyncsContentImages;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    use SyncsContentImages;

    public function index(): View
    {
        $pages = Page::orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        Page::create($request->validated());

        return redirect()->route('admin.pages.index')
            ->with('success', 'Página criada com sucesso.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $data = $request->validated();
        $oldSrcs = $this->extractLocalImageSrcs($page->content ?? '');
        $page->update($data);
        $this->deleteOrphanedImages($oldSrcs, $this->extractLocalImageSrcs($data['content'] ?? ''));

        return redirect()->route('admin.pages.index')
            ->with('success', 'Página atualizada com sucesso.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Página excluída com sucesso.');
    }
}
