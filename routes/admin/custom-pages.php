<?php

use App\Http\Controllers\Admin\CustomPageController;
use Illuminate\Support\Facades\Route;

// Included inside the authenticated admin group (prefix admin, name admin.)
Route::post('custom-pages/{customPage}/toggle', [CustomPageController::class, 'toggle'])->name('custom-pages.toggle');
Route::resource('custom-pages', CustomPageController::class)->except(['show']);
