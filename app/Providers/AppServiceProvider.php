<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SeoService::class);

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Fruitcake\LaravelDebugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('pt_BR');

        View::composer(
            ['layouts.app', 'partials.header', 'partials.footer'],
            function ($view) {
                $categories = Cache::remember('menu_categories', 3600, function () {
                    return Category::whereNull('parent_id')
                        ->with([
                            'children' => fn ($q) => $q->withCount(['posts' => fn ($q) => $q->published()]),
                        ])
                        ->withCount(['posts' => fn ($q) => $q->published()])
                        ->get();
                });

                $footerPages = Cache::remember('footer_pages', 3600, fn () => \App\Models\Page::orderBy('title')->get(['id', 'title', 'slug'])
                );

                $view->with('categories', $categories);
                $view->with('footerPages', $footerPages);
            });
    }
}
