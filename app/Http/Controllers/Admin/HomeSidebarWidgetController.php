<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHomeSidebarWidgetRequest;
use App\Models\HomeContainer;
use App\Models\HomeSidebarWidget;
use Illuminate\Http\RedirectResponse;

class HomeSidebarWidgetController extends Controller
{
    public function store(StoreHomeSidebarWidgetRequest $request, HomeContainer $homeContainer): RedirectResponse
    {
        $data = $request->validated();
        $data['container_id'] = $homeContainer->id;
        $data['position'] = HomeSidebarWidget::where('container_id', $homeContainer->id)->max('position') + 1;
        $data['limit'] = $data['limit'] ?? 5;

        HomeSidebarWidget::create($data);

        return redirect()->route('admin.home-containers.index')
            ->with('success', 'Widget adicionado com sucesso.');
    }

    public function update(StoreHomeSidebarWidgetRequest $request, HomeContainer $homeContainer, HomeSidebarWidget $widget): RedirectResponse
    {
        $widget->update($request->validated());

        return redirect()->route('admin.home-containers.index')
            ->with('success', 'Widget atualizado com sucesso.');
    }

    public function destroy(HomeContainer $homeContainer, HomeSidebarWidget $widget): RedirectResponse
    {
        $widget->delete();

        return redirect()->route('admin.home-containers.index')
            ->with('success', 'Widget removido com sucesso.');
    }

    public function moveUp(HomeContainer $homeContainer, HomeSidebarWidget $widget): RedirectResponse
    {
        $previous = HomeSidebarWidget::where('container_id', $homeContainer->id)
            ->where('position', '<', $widget->position)
            ->orderByDesc('position')
            ->first();

        if ($previous) {
            [$widget->position, $previous->position] = [$previous->position, $widget->position];
            $widget->save();
            $previous->save();
        }

        return redirect()->route('admin.home-containers.index');
    }

    public function moveDown(HomeContainer $homeContainer, HomeSidebarWidget $widget): RedirectResponse
    {
        $next = HomeSidebarWidget::where('container_id', $homeContainer->id)
            ->where('position', '>', $widget->position)
            ->orderBy('position')
            ->first();

        if ($next) {
            [$widget->position, $next->position] = [$next->position, $widget->position];
            $widget->save();
            $next->save();
        }

        return redirect()->route('admin.home-containers.index');
    }
}
