<?php

namespace App\Providers;

use App\DataStructures\HashTable;
use App\Repositories\AI\Clients\AnthropicClient;
use App\Repositories\AI\Clients\OpenAiClient;
use App\Repositories\AI\Clients\OpenRouterClient;
use App\Repositories\Import\Services\ImageBasedPromptsImportService;
use App\Repositories\Import\Services\PromptSettingsImportService;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('ai-clients', fn ($app) => collect());
        $this->app->resolving('ai-clients', function (Collection $clients): void {
            $clients->push(OpenRouterClient::class);
            $clients->push(OpenAiClient::class);
            $clients->push(AnthropicClient::class);
        });

        $this->app->bind('importers', fn ($app) => new HashTable);
        $this->app->resolving('importers', function (HashTable $services): void {
            $services->insert('ts', PromptSettingsImportService::class);
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
