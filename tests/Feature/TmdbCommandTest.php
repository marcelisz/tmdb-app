<?php

namespace Tests\Feature;

use App\Jobs\FetchTmdbData;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TmdbCommandTest extends TestCase
{
    public function test_console_command_dispatches_job(): void
    {
        // Fake the queue so the job is not actually run, just check if it was pushed
        Queue::fake();

        // Run the command
        $this->artisan('tmdb:sync')
            ->assertExitCode(0);

        // Assert the job was pushed to the queue
        Queue::assertPushed(FetchTmdbData::class);
    }
}
