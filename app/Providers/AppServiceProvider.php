<?php

declare(strict_types=1);

namespace App\Providers;

use App\DataStructures\HashTable;
use App\Repositories\AI\Clients\AnthropicClient;
use App\Repositories\AI\Clients\OpenAiClient;
use App\Repositories\AI\Clients\OpenRouterClient;
use App\Repositories\Import\Services\BookOfMatchesImportService;
use App\Repositories\Import\Services\HuggingFaceImportService;
use App\Repositories\Import\Services\KindlepreneurImportService;
use App\Repositories\Import\Services\MediaStudioImportService;
use App\Repositories\Import\Services\ModifiersImportService;
use App\Repositories\Import\Services\MovieCollectionsImportService;
use App\Repositories\Import\Services\NovelStarterImportService;
use App\Repositories\Import\Services\PlotMachineImportService;
use App\Repositories\Import\Services\PromptSettingsImportService;
use App\Repositories\Import\Services\PulpAdventureImportService;
use App\Repositories\Import\Services\RedditEndpointsImportService;
use App\Repositories\Import\Services\SelfPublishingSchoolImportService;
use App\Repositories\Import\Services\StoryGeneratorImportService;
use App\Repositories\Import\Services\StoryMachineImportService;
use App\Repositories\Import\Services\TheLinesImportService;
use App\Repositories\Import\Services\WritersDigestImportService;
use App\Repositories\Prompters\Services\BookOfMatchesPromptService;
use App\Repositories\Prompters\Services\GeneratedPromptService;
use App\Repositories\Prompters\Services\HuggingFacePromptService;
use App\Repositories\Prompters\Services\KindlepreneurPromptService;
use App\Repositories\Prompters\Services\MediaStudioPromptService;
use App\Repositories\Prompters\Services\MovieCollectionItemsPromptService;
use App\Repositories\Prompters\Services\MovieMashupPromptService;
use App\Repositories\Prompters\Services\NewsArticlePromptService;
use App\Repositories\Prompters\Services\NovelStarterPromptService;
use App\Repositories\Prompters\Services\PlotMachinePromptService;
use App\Repositories\Prompters\Services\PulpAdventurePromptService;
use App\Repositories\Prompters\Services\RedditWritingPromptService;
use App\Repositories\Prompters\Services\SelfPublishingSchoolPromptService;
use App\Repositories\Prompters\Services\StoryGeneratorPromptService;
use App\Repositories\Prompters\Services\StoryIdeaPromptService;
use App\Repositories\Prompters\Services\StoryMachinePromptService;
use App\Repositories\Prompters\Services\TheLinesPromptService;
use App\Repositories\Prompters\Services\WritersDigestPromptService;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->bind('ai-clients', fn ($app): Collection => collect());
        $this->app->resolving('ai-clients', function (Collection $clients): void {
            $clients->push(OpenRouterClient::class);
            $clients->push(OpenAiClient::class);
            $clients->push(AnthropicClient::class);
        });

        $this->app->bind('ai-heavy-clients', fn ($app): Collection => collect());
        $this->app->resolving('ai-heavy-clients', function (Collection $clients): void {
            $clients->push(OpenAiClient::class);
            $clients->push(AnthropicClient::class);
        });

        $this->app->bind('importers', fn ($app): HashTable => new HashTable);
        $this->app->resolving('importers', function (HashTable $services): void {
            $services->insert('ps', PromptSettingsImportService::class);
            $services->insert('bm', BookOfMatchesImportService::class);
            $services->insert('pm', PlotMachineImportService::class);
            $services->insert('sm', StoryMachineImportService::class);
            $services->insert('ag', StoryGeneratorImportService::class);
            $services->insert('pa', PulpAdventureImportService::class);
            $services->insert('hf', HuggingFaceImportService::class);
            $services->insert('ns', NovelStarterImportService::class);
            $services->insert('fl', TheLinesImportService::class);
            $services->insert('mf', ModifiersImportService::class);
            $services->insert('mc', MovieCollectionsImportService::class);
            $services->insert('ms', MediaStudioImportService::class);
            $services->insert('rd', RedditEndpointsImportService::class);
            $services->insert('wd', WritersDigestImportService::class);
            $services->insert('ke', KindlepreneurImportService::class);
            $services->insert('sp', SelfPublishingSchoolImportService::class);
        });

        $this->app->bind('prompters', fn ($app): Collection => collect());
        $this->app->resolving('prompters', function (Collection $prompters): void {
            $prompters->push(BookOfMatchesPromptService::class);
            $prompters->push(GeneratedPromptService::class);
            $prompters->push(HuggingFacePromptService::class);
            $prompters->push(KindlepreneurPromptService::class);
            $prompters->push(MediaStudioPromptService::class);
            $prompters->push(MovieCollectionItemsPromptService::class);
            $prompters->push(MovieMashupPromptService::class);
            $prompters->push(NewsArticlePromptService::class);
            $prompters->push(NovelStarterPromptService::class);
            $prompters->push(PlotMachinePromptService::class);
            $prompters->push(PulpAdventurePromptService::class);
            $prompters->push(RedditWritingPromptService::class);
            $prompters->push(SelfPublishingSchoolPromptService::class);
            $prompters->push(StoryGeneratorPromptService::class);
            $prompters->push(StoryIdeaPromptService::class);
            $prompters->push(StoryMachinePromptService::class);
            $prompters->push(TheLinesPromptService::class);
            $prompters->push(WritersDigestPromptService::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
