<?php

use App\Http\Controllers\ConfigController;

Route::get('/configuraciones', [ConfigController::class, 'show']);
Route::put('/configuraciones', [ConfigController::class, 'update']);