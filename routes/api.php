<?php

use App\Http\Controllers\Api\IaDraftController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.token')->post('/ia/draft', IaDraftController::class);