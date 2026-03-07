<?php

declare(strict_types=1);

namespace App\Providers;

use App\DataStructures\HashTable;
use App\Repositories\AI\Clients\AnthropicClient;
use App\Repositories\AI\Clients\DeepSeekClient;
use App\Repositories\AI\Clients\GeminiClient;
use App\Repositories\AI\Clients\OllamaClient;
use App\Repositories\AI\Clients\OpenAiClient;
use App\Repositories\AI\Clients\OpenRouterClient;
use App\Repositories\Import\Services\BookOfMatchesImportService;
use App\Repositories\Import\Services\ElegantLiteraturePromptImportService;
use App\Repositories\Import\Services\GenreImportService;
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
use App\Repositories\Prompters\Services\ElegantLiteraturePromptService;
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
use App\Repositories\Prompters\Services\ShortStoryOutlinePromptService;
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
        $this->app->bind('ai-weighted-clients', fn ($app): Collection => collect());
        $this->app->resolving('ai-weighted-clients', function (Collection $clients): void {
            $clients->push([
                'class' => OllamaClient::class,
                'weight' => 900,
            ]);
            $clients->push([
                'class' => GeminiClient::class,
                'weight' => 700,
            ]);
            $clients->push([
                'class' => OpenRouterClient::class,
                'weight' => 500,
            ]);
            $clients->push([
                'class' => OpenAiClient::class,
                'weight' => 400,
            ]);
            $clients->push([
                'class' => AnthropicClient::class,
                'weight' => 200,
            ]);
            $clients->push([
                'class' => DeepSeekClient::class,
                'weight' => 100,
            ]);
        });

        $this->app->bind('ai-document-clients', fn ($app): Collection => collect());
        $this->app->resolving('ai-document-clients', function (Collection $clients): void {
            $clients->push(OpenAiClient::class);
            $clients->push(AnthropicClient::class);
            $clients->push(GeminiClient::class);
        });

        $this->app->bind('ai-heavy-clients', fn ($app): Collection => collect());
        $this->app->resolving('ai-heavy-clients', function (Collection $clients): void {
            $clients->push(OpenAiClient::class);
            $clients->push(AnthropicClient::class);
            $clients->push(GeminiClient::class);
            $clients->push(DeepSeekClient::class);
            $clients->push(OpenRouterClient::class);
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
            $services->insert('el', ElegantLiteraturePromptImportService::class);
            $services->insert('gn', GenreImportService::class);
        });

        $this->app->bind('prompters', fn ($app): Collection => collect());
        $this->app->resolving('prompters', function (Collection $prompters): void {
            $prompters->push([
                'key' => 'bm',
                'value' => BookOfMatchesPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'el',
                'value' => ElegantLiteraturePromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'gp',
                'value' => GeneratedPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'hf',
                'value' => HuggingFacePromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'kp',
                'value' => KindlepreneurPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'ms',
                'value' => MediaStudioPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'mc',
                'value' => MovieCollectionItemsPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'mm',
                'value' => MovieMashupPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'na',
                'value' => NewsArticlePromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'pa',
                'value' => PulpAdventurePromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'rw',
                'value' => RedditWritingPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'sp',
                'value' => SelfPublishingSchoolPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'ss',
                'value' => ShortStoryOutlinePromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'sg',
                'value' => StoryGeneratorPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'si',
                'value' => StoryIdeaPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'sm',
                'value' => StoryMachinePromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'tl',
                'value' => TheLinesPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'wd',
                'value' => WritersDigestPromptService::class,
            ]); // Done
            $prompters->push([
                'key' => 'ns',
                'value' => NovelStarterPromptService::class,
            ]); // Done
//            $prompters->push([
//                'key' => 'pm',
//                'value' => PlotMachinePromptService::class, // TODO: pending AI generation
//            ]);
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
