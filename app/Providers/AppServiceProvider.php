<?php

namespace App\Providers;

use App\DataStructures\HashTable;
use App\Repositories\AI\Clients\AnthropicClient;
use App\Repositories\AI\Clients\OpenAiClient;
use App\Repositories\AI\Clients\OpenRouterClient;
use App\Repositories\Import\Services\BookOfMatchesImportService;
use App\Repositories\Import\Services\ImageBasedPromptsImportService;
use App\Repositories\Import\Services\NovelStarterImportService;
use App\Repositories\Import\Services\PlotMachineImportService;
use App\Repositories\Import\Services\PromptSettingsImportService;
use App\Repositories\Import\Services\PulpAdventureImportService;
use App\Repositories\Import\Services\StoryGeneratorImportService;
use App\Repositories\Import\Services\StoryMachineImportService;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('ai-clients', fn ($app): Collection => collect());
        $this->app->resolving('ai-clients', function (Collection $clients): void {
            $clients->push(OpenRouterClient::class);
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
            $services->insert('ns', NovelStarterImportService::class);
            $services->insert('ib', ImageBasedPromptsImportService::class);
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
