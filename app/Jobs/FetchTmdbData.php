<?php

namespace App\Jobs;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchTmdbData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $languages = ['en', 'pl', 'de'];
    protected $apiKey;
    protected $baseUrl = 'https://api.themoviedb.org/3'; // TMDB API version 3

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key') ?? env('TMDB_API_KEY');
    }

    public function handle(): void
    {
        $this->syncGenres();
        $this->syncMovies();
        $this->syncSeries();
    }

    private function syncGenres()
    {
        // Fetch all genres listed in TMDB
        foreach ($this->languages as $lang) {
            $response = Http::get("{$this->baseUrl}/genre/movie/list", [
                'api_key' => $this->apiKey,
                'language' => $lang,
            ]);

            if ($response->successful()) {
                foreach ($response->json('genres') as $item) {
                    $genre = Genre::firstOrNew(['tmdb_id' => $item['id']]);
                    $name = $genre->name ?? [];
                    $name[$lang] = $item['name'];
                    $genre->name = $name;
                    $genre->save();
                }
            }
        }
    }

    private function syncMovies()
    {
        // Fetch 50 records (TMDB returns 20 per page, so results will be spread over 3 pages)
        $pagesToFetch = 3;

        foreach ($this->languages as $lang) {
            $count = 0;
            for ($page = 1; $page <= $pagesToFetch; $page++) {
                $response = Http::get("{$this->baseUrl}/movie/popular", [
                    'api_key' => $this->apiKey,
                    'language' => $lang,
                    'page' => $page
                ]);

                if ($response->successful()) {
                    foreach ($response->json('results') as $item) {
                        if ($count >= 50) break; // Hard limit

                        $movie = Movie::firstOrNew(['tmdb_id' => $item['id']]);

                        // Merge titles
                        $titles = $movie->title ?? [];
                        $titles[$lang] = $item['title'];
                        $movie->title = $titles;

                        // Merge overviews
                        $overviews = $movie->overview ?? [];
                        $overviews[$lang] = $item['overview'];
                        $movie->overview = $overviews;
                        $movie->release_date = $item['release_date'] ?? null;

                        $movie->save();
                        $count++;
                    }
                }
            }
        }
    }

    private function syncSeries()
    {
        // Fetch 10 records (one page is enough)
        foreach ($this->languages as $lang) {
            $count = 0;
            $response = Http::get("{$this->baseUrl}/tv/popular", [
                'api_key' => $this->apiKey,
                'language' => $lang,
            ]);

            if ($response->successful()) {
                foreach ($response->json('results') as $item) {
                    if ($count >= 10) break;

                    $serie = Serie::firstOrNew(['tmdb_id' => $item['id']]);

                    // Merge titles
                    $names = $serie->name ?? [];
                    $names[$lang] = $item['name'];
                    $serie->name = $names;

                    // Merge overviews
                    $overviews = $serie->overview ?? [];
                    $overviews[$lang] = $item['overview'];
                    $serie->overview = $overviews;
                    $serie->first_air_date = $item['first_air_date'] ?? null;

                    $serie->save();
                    $count++;
                }
            }
        }
    }
}
