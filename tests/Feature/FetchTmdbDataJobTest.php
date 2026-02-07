<?php

namespace Tests\Feature;

use App\Jobs\FetchTmdbData;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchTmdbDataJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_fetches_and_stores_data_correctly(): void
    {
        // 1. Mock external TMDB API responses
        Http::fake([
            // Genres
            '*/genre/movie/list*' => Http::response([
                'genres' => [
                    ['id' => 100, 'name' => 'Action Mock']
                ]
            ], 200),

            // Movies (return 2 results to test looping, though job limits to 50)
            '*/movie/popular*' => Http::response([
                'results' => [
                    [
                        'id' => 500,
                        'title' => 'Movie Mock',
                        'overview' => 'Overview mock',
                        'release_date' => '2023-01-01'
                    ]
                ]
            ], 200),

            // Series
            '*/tv/popular*' => Http::response([
                'results' => [
                    [
                        'id' => 800,
                        'name' => 'Serie Mock',
                        'overview' => 'Serie overview mock',
                        'first_air_date' => '2023-02-01'
                    ]
                ]
            ], 200),
        ]);

        // 2. Dispatch the job synchronously (run immediately)
        (new FetchTmdbData())->handle();

        // 3. Assert created database records
        $this->assertDatabaseCount('genres', 1);
        $this->assertDatabaseCount('movies', 1);
        $this->assertDatabaseCount('series', 1);

        // 4. Assert JSON column structure (multi-language merging)
        // Since the mock returns the same string for all calls in the loop,
        // the JSON is expected to have keys for 'en', 'pl', 'de' with the mocked value

        $movie = Movie::first();
        $this->assertEquals('Movie Mock', $movie->title['en']);
        $this->assertEquals('Movie Mock', $movie->title['pl']);
        $this->assertEquals(500, $movie->tmdb_id);

        $genre = Genre::first();
        $this->assertEquals('Action Mock', $genre->name['en']);

        $serie = Serie::first();
        $this->assertEquals('Serie Mock', $serie->name['de']);
    }
}
