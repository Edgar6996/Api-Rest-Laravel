<?php

use App\Http\Controllers\ConfigController;

//Route::middleware('user.admin')->group(function(){
    Route::get('/configuraciones', [ConfigController::class, 'show']);
    Route::put('/configuraciones', [ConfigController::class, 'update'])->middleware('user.admin');
//});

