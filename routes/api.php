<?php
use  App\Http\Controllers\PlatformController;

Route::prefix('platforms')->controller(PlatformController::class)->name('platforms.')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});