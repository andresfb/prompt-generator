<?php

declare(strict_types=1);

use App\Jobs\AiNotificationSummaryJob;
use App\Jobs\CreateMovieMashupJob;
use App\Jobs\CreateNovelStarterPromptJob;
use App\Jobs\CreatePlotMachinePromptJob;
use App\Jobs\GenerateMovieMashupPromptJob;
use App\Jobs\GenerateNovelStarterPromptJob;
use App\Jobs\GeneratePlotMachinePromptJob;
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

// Runs every two hours at 5 past the hour
Schedule::job(app(CreateNovelStarterPromptJob::class))->everyTwoHours(2);

// Runs every two hours at 5 past the hour
Schedule::job(app(CreatePlotMachinePromptJob::class))->everyTwoHours(5);

// Runs 4 times a day at 8 past the hour
Schedule::job(app(GeneratePromptJob::class))->everySixHours(8);

// Runs 4 times a day at 12 past the hour
Schedule::job(app(GeneratePlotMachinePromptJob::class))->everySixHours(12);

// Runs 4 times a day at 16 past the hour
Schedule::job(app(GenerateNovelStarterPromptJob::class))->everySixHours(16);

// Runs 4 times a day at 20 past the hour
Schedule::job(app(CreateMovieMashupJob::class))->everySixHours(20);

// Runs 4 times a day at 25 past the hour
Schedule::job(app(NewsArticleExtractorJob::class))->everySixHours(25);

// Three times a day at 3:30 AM, 11:30 AM, and 7:30 PM
Schedule::job(app(GenerateMovieMashupPromptJob::class))->cron('30 3,11,19 * * *');

// Twice a day at 5 AM and 5 PM
Schedule::job(app(GenerateShortStoryOutlineJob::class))->twiceDaily(5, 17);

// Once a day at 4:10 PM
Schedule::job(app(MediaStudioStarterJob::class))->dailyAt('16:18');

// Once a day at 10:15 pm
Schedule::job(app(RedditPromptsStarterJob::class))->dailyAt('22:15');

// Once a day at 11:50 pm
Schedule::job(app(AiNotificationSummaryJob::class))->dailyAt('23:50');

// Weekly
Schedule::job(app(ImportMovieCollectionItemsJob::class))->weekly();
