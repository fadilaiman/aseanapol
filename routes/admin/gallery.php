<?php

use App\Http\Controllers\Admin\GalleryController;
use Illuminate\Support\Facades\Route;

// Included inside the authenticated admin group (prefix admin, name admin.)
Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
Route::post('gallery/toggle', [GalleryController::class, 'toggle'])->name('gallery.toggle');
Route::delete('gallery', [GalleryController::class, 'destroy'])->name('gallery.destroy');
