<?php

declare(strict_types=1);

use App\Jobs\GenerateMovieMashupPromptJob;
use App\Jobs\GeneratePromptJob;
use App\Jobs\NewsArticleExtractorJob;
use App\Jobs\RedditPromptsStarterJob;
use App\Repositories\Search\CreateMovieMashupService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Runs 4 times a day on the 5th minute of the hour
Schedule::job(app(GeneratePromptJob::class))->everySixHours(5);

// Runs 4 times a day on the 10th minute of the hour
Schedule::job(app(CreateMovieMashupService::class))->everySixHours(10);

// Twice a day at 2 AM and 2 PM
Schedule::job(app(NewsArticleExtractorJob::class))->twiceDaily(2, 14);

// Twice a day at 3 AM and 3 PM
Schedule::job(app(GenerateMovieMashupPromptJob::class))->twiceDaily(3, 15);

// Once a day at 10:15 pm
Schedule::job(app(RedditPromptsStarterJob::class))->dailyAt('22:15');
