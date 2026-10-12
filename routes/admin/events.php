<?php

use App\Http\Controllers\Admin\EventController;
use Illuminate\Support\Facades\Route;

// Included inside the authenticated /admin group (see routes/web.php).
Route::post('events/{event}/toggle', [EventController::class, 'toggle'])->name('events.toggle');
Route::resource('events', EventController::class)->except(['show']);
