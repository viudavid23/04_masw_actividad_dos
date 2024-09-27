<?php
use App\Http\Controllers\ActorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::prefix('platforms')->controller(PlatformController::class)->name('platforms.')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('languages')->controller(LanguageController::class)->name('languages.')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('actors')->controller(ActorController::class)->name('actors.')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});