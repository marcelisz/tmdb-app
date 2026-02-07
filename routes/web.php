<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\MoviesList;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/movies-list', MoviesList::class)->name('movies.list');
