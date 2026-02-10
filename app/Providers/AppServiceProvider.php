<?php

namespace App\Providers;

use App\Services\AI\Clients\OpenRouterClient;
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
