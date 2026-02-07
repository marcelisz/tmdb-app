# Laravel TMDB multi-language API

This application retrieves data from TMDB (The Movie Database), stores it locally in a database, and exposes a REST API with support for multiple languages.

## Requirements
- PHP 8.2+
- Composer
- MySQL (or other compatible database)
- Laravel 12+

## Setup

1. **Clone repository and install dependencies:**
   ```bash
   composer install
   ```
   
2. **Environment setup:**
   
   Copy `.env.example` to `.env` and configure:
   - Database credentials (for MySQL - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
   - `QUEUE_CONNECTION=database`
   - TMDB API key (`TMDB_API_KEY=your_key_here`) - create a free key here: https://www.themoviedb.org/settings/api

3. **Database and migrations:**
   
   This will create the necessary tables in the database as described in the migration files in `/database/migrations` directory:
   ```bash
   php artisan migrate
   ```

4. **Fetch data:**
   
   The data fetching is handled via a Job. First, start the queue worker:
   ```bash
   php artisan queue:work
   ```
   
   In a separate terminal, dispatch the import job:
   ```bash
   php artisan tmdb:sync
   ```
   *Note: This will fetch 50 movies, 10 TV series, and all genres in 3 languages.*
   
5. **Serve the app:**
   ```bash
   php artisan serve
   ```

## Example usage

Fetch data about movies stored in the database in Polish language:
```bash
curl -H "Accept-Language: pl" http://127.0.0.1:8000/api/movies
```

*Note: The above command lists first 20 records from the Movie table. To get more records, add `?page=X` to the API address, where X is the page number.*

## API endpoints

**Available endpoints:**
- Movies: **GET** `/api/movies`
- TV series: **GET** `/api/series`
- Genres: **GET** `/api/genres`

All endpoints support pagination (Laravel default). Language is determined by the `Accept-Language` header (example: `Accept-Language: en`).
Supported codes: `en`, `pl`, `de` (default: `en`).

## Data models
- **Movie**: Stores title/overview in JSON for multiple languages.
- **Serie**: Stores name/overview in JSON.
- **Genre**: Stores name in JSON.

## Testing

The application includes a suite of Feature tests that ensure the integrity of the data ingestion process and API responses without hitting the real TMDB API. To execute the tests, run the following command:
```bash
php artisan test
```
The following elements are tested:
- **Console command** - verifies that `php artisan tmdb:sync` command correctly dispatches the job to the queue.
- **Job logic** - verifies that `FetchTmdbData` class handles multi-page fetching and language merging correctly.
- **API endpoints** - verifies JSON structure, pagination, and `Accept-Language` header handling (e.g., requesting Polish data returns Polish titles).

## Livewire frontend

A Livewire web interface that lists movies saved in the database is available on the `/movies-list` endpoint. It displays movie details in grid layout and supports pagination, offering seamless page switching without full reloads.