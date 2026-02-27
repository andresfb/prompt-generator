<?php

declare(strict_types=1);

use App\Jobs\CreateMovieMashupJob;
use App\Jobs\GenerateMovieMashupPromptJob;
use App\Jobs\GeneratePromptJob;
use App\Jobs\GenerateShortStoryOutlineJob;
use App\Jobs\ImportMovieCollectionItemsJob;
use App\Jobs\MediaStudioStarterJob;
use App\Jobs\NewsArticleExtractorJob;
use App\Jobs\RedditPromptsStarterJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Once a day at 10:15 pm
Schedule::job(app(RedditPromptsStarterJob::class))->dailyAt('22:15');

// Runs 4 times a day at 15 past the hour
Schedule::job(app(GeneratePromptJob::class))->everySixHours(15);

// Runs 4 times a day at 20 past the hour
Schedule::job(app(CreateMovieMashupJob::class))->everySixHours(20);

// Runs 4 times a day at 25 past the hour
Schedule::job(app(NewsArticleExtractorJob::class))->everySixHours(25);

// Twice a day at 3 AM and 3 PM
Schedule::job(app(GenerateMovieMashupPromptJob::class))->twiceDaily(3, 15);

// Twice a day at 4 AM and 4 PM
Schedule::job(app(MediaStudioStarterJob::class))->twiceDaily(4, 16);

// Twice a day at 5 AM and 5 PM
Schedule::job(app(GenerateShortStoryOutlineJob::class))->twiceDaily(5, 17);

// Weekly
Schedule::job(app(ImportMovieCollectionItemsJob::class))->weekly();
