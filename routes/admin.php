<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EditorController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\HomeContainerController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\HomeSidebarWidgetController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;

// --- Autenticação (sem middleware admin) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// --- Área protegida ---
Route::middleware(['auth', 'admin', 'admin.security'])->group(function (): void {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('pages', PageController::class);

    Route::post('editor/upload-image', [EditorController::class, 'uploadImage'])->name('editor.upload-image');

    Route::post('cache/clear', [CacheController::class, 'clear'])->name('cache.clear');

    Route::resource('home-sections', HomeSectionController::class)->except(['index']);
    Route::post('home-sections/{homeSection}/move-up', [HomeSectionController::class, 'moveUp'])->name('home-sections.move-up');
    Route::post('home-sections/{homeSection}/move-down', [HomeSectionController::class, 'moveDown'])->name('home-sections.move-down');

    // Layout containers
    Route::resource('home-containers', HomeContainerController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('home-containers/{homeContainer}/move-up', [HomeContainerController::class, 'moveUp'])->name('home-containers.move-up');
    Route::post('home-containers/{homeContainer}/move-down', [HomeContainerController::class, 'moveDown'])->name('home-containers.move-down');

    // Sidebar widgets (nested)
    Route::post('home-containers/{homeContainer}/widgets', [HomeSidebarWidgetController::class, 'store'])->name('home-containers.widgets.store');
    Route::patch('home-containers/{homeContainer}/widgets/{widget}', [HomeSidebarWidgetController::class, 'update'])->name('home-containers.widgets.update');
    Route::delete('home-containers/{homeContainer}/widgets/{widget}', [HomeSidebarWidgetController::class, 'destroy'])->name('home-containers.widgets.destroy');
    Route::post('home-containers/{homeContainer}/widgets/{widget}/move-up', [HomeSidebarWidgetController::class, 'moveUp'])->name('home-containers.widgets.move-up');
    Route::post('home-containers/{homeContainer}/widgets/{widget}/move-down', [HomeSidebarWidgetController::class, 'moveDown'])->name('home-containers.widgets.move-down');
});
