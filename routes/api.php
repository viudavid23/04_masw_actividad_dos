<?php
use App\Http\Controllers\ActorController;
use App\Http\Controllers\ActorSerieController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\DirectorSerieController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\PlatformSerieController;
use App\Http\Controllers\SerieController;
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

Route::prefix('directors')->controller(DirectorController::class)->name('directors.')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('series')->controller(SerieController::class)->name('series.')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('platform-series')->controller(PlatformSerieController::class)->name('platform series.')->group(function () {
    Route::get('/', 'index');
    Route::get('/serie/{id}', 'showBySerie');
    Route::get('/platform/{id}', 'showByPlatform');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('actor-series')->controller(ActorSerieController::class)->name('actor series.')->group(function () {
    Route::get('/', 'index');
    Route::get('/serie/{id}', 'showBySerie');
    Route::get('/actor/{id}', 'showByActor');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('director-series')->controller(DirectorSerieController::class)->name('director series.')->group(function () {
    Route::get('/', 'index');
    Route::get('/serie/{id}', 'showBySerie');
    Route::get('/director/{id}', 'showByDirector');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});