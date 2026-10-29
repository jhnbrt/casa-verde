<?php

namespace App\Console\Commands;

use App\Services\GoogleReviews;
use Illuminate\Console\Command;
use Throwable;

class FindGooglePlace extends Command
{
    protected $signature = 'reviews:find-google-place {query=Casa Verde Cliff Resort and Spa Camotes}';

    protected $description = 'Look up the Google Place ID to put in GOOGLE_PLACE_ID';

    public function handle(GoogleReviews $google): int
    {
        try {
            $places = $google->findPlace((string) $this->argument('query'));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if ($places === []) {
            $this->warn('Google found no place for that search. Try a different name.');

            return self::FAILURE;
        }

        $this->table(['Place ID', 'Name', 'Address', 'Rating', 'Reviews'], array_map(
            fn (array $p) => [$p['id'], $p['name'], $p['address'], $p['rating'], $p['count']],
            $places,
        ));

        $this->info('Copy the Place ID of the right row into GOOGLE_PLACE_ID in the .env file.');

        return self::SUCCESS;
    }
}
