<?php

namespace App\Console\Commands;

use App\Services\GoogleReviews;
use Illuminate\Console\Command;
use Throwable;

class SyncGoogleReviews extends Command
{
    protected $signature = 'reviews:sync-google';

    protected $description = 'Copy the resort\'s Google reviews into the Reviews page';

    public function handle(GoogleReviews $google): int
    {
        try {
            $result = $google->sync();
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Google returned {$result['fetched']} review(s); {$result['saved']} saved.");

        return self::SUCCESS;
    }
}
