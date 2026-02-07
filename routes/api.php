<?php

use App\Http\Controllers\Api\TmdbController;
use Illuminate\Support\Facades\Route;

Route::middleware('localize')->group(function () {
    Route::get('/movies', [TmdbController::class, 'getMovies']);
    Route::get('/series', [TmdbController::class, 'getSeries']);
    Route::get('/genres', [TmdbController::class, 'getGenres']);
});
