<?php

use App\Http\Controllers\Admin\PublicationController;
use Illuminate\Support\Facades\Route;

// Included inside the authenticated admin group (prefix admin, name admin.)
Route::post('publications/{publication}/toggle', [PublicationController::class, 'toggle'])->name('publications.toggle');
Route::resource('publications', PublicationController::class)->except(['show']);
