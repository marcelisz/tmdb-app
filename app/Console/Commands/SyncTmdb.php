<?php

namespace App\Console\Commands;

use App\Jobs\FetchTmdbData;
use Illuminate\Console\Command;

class SyncTmdb extends Command
{
    protected $signature = 'tmdb:sync';
    protected $description = 'Dispatch job to fetch data from TMDB API';

    public function handle(): void
    {
        $this->info('Dispatching TMDB data fetch job...');
        FetchTmdbData::dispatch();
        $this->info('Job dispatched to queue.');
    }
}
