<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the news scraper to run daily at 6:00 AM
Schedule::command('scrape:incendios-news')
    ->dailyAt('06:00')
    ->timezone('America/La_Paz')
    ->onSuccess(function () {
        \Log::info('News scraping completed successfully');
    })
    ->onFailure(function () {
        \Log::error('News scraping failed');
    });
