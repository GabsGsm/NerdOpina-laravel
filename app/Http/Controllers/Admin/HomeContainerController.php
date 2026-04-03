<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHomeContainerRequest;
use App\Models\HomeContainer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeContainerController extends Controller
{
    public function index(): View
    {
        $containers = HomeContainer::orderBy('position')
            ->with([
                'sections' => fn ($q) => $q->orderBy('position'),
                'sidebarWidgets' => fn ($q) => $q->orderBy('position'),
            ])
            ->withCount(['sections', 'sidebarWidgets'])
            ->get();

        return view('admin.home-containers.index', compact('containers'));
    }

    public function store(StoreHomeContainerRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['position'] = HomeContainer::max('position') + 1;

        HomeContainer::create($data);

        return redirect()->route('admin.home-containers.index')
            ->with('success', 'Container criado com sucesso.');
    }

    public function update(StoreHomeContainerRequest $request, HomeContainer $homeContainer): RedirectResponse
    {
        $homeContainer->update($request->validated());

        return redirect()->route('admin.home-containers.index')
            ->with('success', 'Container atualizado com sucesso.');
    }

    public function destroy(HomeContainer $homeContainer): RedirectResponse
    {
        $homeContainer->delete();

        return redirect()->route('admin.home-containers.index')
            ->with('success', 'Container excluído com sucesso.');
    }

    public function moveUp(HomeContainer $homeContainer): RedirectResponse
    {
        $previous = HomeContainer::where('position', '<', $homeContainer->position)
            ->orderByDesc('position')
            ->first();

        if ($previous) {
            [$homeContainer->position, $previous->position] = [$previous->position, $homeContainer->position];
            $homeContainer->save();
            $previous->save();
        }

        return redirect()->route('admin.home-containers.index');
    }

    public function moveDown(HomeContainer $homeContainer): RedirectResponse
    {
        $next = HomeContainer::where('position', '>', $homeContainer->position)
            ->orderBy('position')
            ->first();

        if ($next) {
            [$homeContainer->position, $next->position] = [$next->position, $homeContainer->position];
            $homeContainer->save();
            $next->save();
        }

        return redirect()->route('admin.home-containers.index');
    }
}
