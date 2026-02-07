<?php

namespace Tests\Feature\Api;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TmdbApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create dummy data with specific translations
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a movie with distinct languages
        Movie::create([
            'tmdb_id' => 1,
            'title' => [
                'en' => 'Some Movie',
                'pl' => 'Jakiś film',
                'de' => 'Ein Film'
            ],
            'overview' => ['en' => 'Description', 'pl' => 'Opis', 'de' => 'Beschreibung'],
            'release_date' => '2023-01-01',
        ]);

        // Create a genre
        Genre::create([
            'tmdb_id' => 10,
            'name' => ['en' => 'Horror', 'pl' => 'Horror PL', 'de' => 'Horror DE'],
        ]);

        // Create a TV show
        Serie::create([
            'tmdb_id' => 20,
            'name' => [
                'en' => 'Some TV Show',
                'pl' => 'Jakiś serial',
                'de' => 'Eine Serie'
            ],
            'overview' => ['en' => 'Description', 'pl' => 'Opis', 'de' => 'Beschreibung'],
            'first_air_date' => '2023-01-01',
        ]);
    }

    public function test_api_returns_movies_in_default_language_english(): void
    {
        $response = $this->getJson('/api/movies');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Some Movie');
    }

    public function test_api_returns_movies_in_polish_via_header(): void
    {
        $response = $this->getJson('/api/movies', [
            'Accept-Language' => 'pl'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Jakiś film');
    }

    public function test_api_returns_movies_in_german_via_header(): void
    {
        $response = $this->getJson('/api/movies', [
            'Accept-Language' => 'de'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Ein Film');
    }

    public function test_api_falls_back_to_english_for_unsupported_language(): void
    {
        // 'fr' is not supported in the middleware logic, should fall back to 'en'
        $response = $this->getJson('/api/movies', [
            'Accept-Language' => 'fr'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Some Movie');
    }

    public function test_genres_endpoint_structure_and_localization(): void
    {
        $response = $this->getJson('/api/genres', [
            'Accept-Language' => 'pl'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Horror PL');
    }

    public function test_api_returns_shows_in_default_language_english(): void
    {
        $response = $this->getJson('/api/series');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Some TV Show');
    }

    public function test_api_returns_shows_in_polish_via_header(): void
    {
        $response = $this->getJson('/api/series', [
            'Accept-Language' => 'pl'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Jakiś serial');
    }

    public function test_api_returns_shows_in_german_via_header(): void
    {
        $response = $this->getJson('/api/series', [
            'Accept-Language' => 'de'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Eine Serie');
    }

    public function test_pagination_meta_data_exists(): void
    {
        // There is one movie in the database, but the structure should still contain meta/links
        $response = $this->getJson('/api/movies');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta' => [
                    'current_page',
                    'last_page',
                    'total'
                ]
            ]);
    }
}
