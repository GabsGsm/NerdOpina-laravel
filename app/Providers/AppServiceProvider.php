<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SeoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('pt_BR');

        View::composer(
            ['layouts.app', 'partials.header'],
            function ($view) {

            $categories = Cache::remember('menu_categories', 3600, function () {
                return Category::whereNull('parent_id')
                    ->with([
                        'children' => fn($q) =>
                            $q->withCount(['posts' => fn($q) => $q->published()])
                    ])
                    ->withCount(['posts' => fn($q) => $q->published()])
                    ->get();
            });

            $view->with('categories', $categories);
        });
    }
}
