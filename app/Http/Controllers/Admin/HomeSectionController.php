<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHomeSectionRequest;
use App\Http\Requests\Admin\UpdateHomeSectionRequest;
use App\Models\Category;
use App\Models\HomeSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeSectionController extends Controller
{
    public function index(): View
    {
        $sections = HomeSection::orderBy('position')->get();

        return view('admin.home-sections.index', compact('sections'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.home-sections.create', compact('categories'));
    }

    public function store(StoreHomeSectionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['position'] = HomeSection::max('position') + 1;
        $data['category_ids'] = $data['category_ids'] ?? null;

        HomeSection::create($data);

        return redirect()->route('admin.home-sections.index')
            ->with('success', 'Seção criada com sucesso.');
    }

    public function edit(HomeSection $homeSection): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.home-sections.edit', compact('homeSection', 'categories'));
    }

    public function update(UpdateHomeSectionRequest $request, HomeSection $homeSection): RedirectResponse
    {
        $data = $request->validated();
        $data['category_ids'] = $data['category_ids'] ?? null;

        $homeSection->update($data);

        return redirect()->route('admin.home-sections.index')
            ->with('success', 'Seção atualizada com sucesso.');
    }

    public function destroy(HomeSection $homeSection): RedirectResponse
    {
        $homeSection->delete();

        return redirect()->route('admin.home-sections.index')
            ->with('success', 'Seção excluída com sucesso.');
    }

    public function moveUp(HomeSection $homeSection): RedirectResponse
    {
        $previous = HomeSection::where('position', '<', $homeSection->position)
            ->orderByDesc('position')
            ->first();

        if ($previous) {
            [$homeSection->position, $previous->position] = [$previous->position, $homeSection->position];
            $homeSection->save();
            $previous->save();
        }

        return redirect()->route('admin.home-sections.index');
    }

    public function moveDown(HomeSection $homeSection): RedirectResponse
    {
        $next = HomeSection::where('position', '>', $homeSection->position)
            ->orderBy('position')
            ->first();

        if ($next) {
            [$homeSection->position, $next->position] = [$next->position, $homeSection->position];
            $homeSection->save();
            $next->save();
        }

        return redirect()->route('admin.home-sections.index');
    }
}
