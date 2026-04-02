<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeSectionController;
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

    Route::resource('home-sections', HomeSectionController::class);
    Route::post('home-sections/{homeSection}/move-up', [HomeSectionController::class, 'moveUp'])->name('home-sections.move-up');
    Route::post('home-sections/{homeSection}/move-down', [HomeSectionController::class, 'moveDown'])->name('home-sections.move-down');
});
