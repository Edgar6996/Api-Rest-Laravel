<?php

use App\Http\Controllers\ConfigController;

Route::get('/configuraciones', [ConfigController::class, 'show']);