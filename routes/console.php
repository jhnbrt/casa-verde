<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Keep the Google reviews up to date (needs the usual "php artisan schedule:run" cron entry).
Illuminate\Support\Facades\Schedule::command('reviews:sync-google')->dailyAt('03:00')->withoutOverlapping();
