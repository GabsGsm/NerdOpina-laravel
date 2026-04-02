<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/busca', [PostController::class, 'search'])->name('search');

// SEO técnico
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/feed.rss', [FeedController::class, 'rss'])->name('feed.rss');
Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nSitemap: ".route('sitemap');

    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');

// Páginas estáticas
Route::get('/sobre', [PageController::class, 'sobre'])->name('pages.sobre');
Route::get('/contato', [PageController::class, 'contato'])->name('pages.contato');

// Categorias
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Páginas geradas no admin
Route::get('/pagina/{slug}', [PageController::class, 'show'])->name('pages.show');

// Imagens do storage
Route::get('/img/{path}', [ImageController::class, 'show'])
    ->where('path', '.+')
    ->name('img');

// Posts — {slug}
Route::get('/{slug}', [PostController::class, 'show'])->name('posts.show');
