<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;
use App\Http\Resources\SerieResource;

class TmdbController extends Controller
{
    public function getMovies()
    {
        // Simple pagination
        return MovieResource::collection(Movie::paginate(20));
    }

    public function getSeries()
    {
        return SerieResource::collection(Serie::paginate(20));
    }

    public function getGenres()
    {
        return GenreResource::collection(Genre::paginate(50));
    }
}
