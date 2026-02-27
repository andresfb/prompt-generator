<?php

declare(strict_types=1);

namespace App\Repositories\AI\Factories;

use App\Repositories\AI\Interfaces\AiClientInterface;
use Illuminate\Support\Collection;
use RuntimeException;

final class AiClientFactory
{
    public static function getClient(): AiClientInterface
    {
        $clients = app('ai-clients');
        if (! $clients instanceof Collection) {
            throw new RuntimeException('No AI Clients available');
        }

        $clientClass = $clients->random();
        $client = app($clientClass);
        if (! $client instanceof AiClientInterface) {
            throw new RuntimeException('No AI Client found');
        }

        return $client;
    }

    public static function getWeightedClient(): AiClientInterface
    {
        $clients = app('ai-weighted-clients');
        if (! $clients instanceof Collection) {
            throw new RuntimeException('No AI Clients available');
        }

        $clientClass = $clients->sortBy(function (array $item): float {
            return -log(mt_rand() / mt_getrandmax()) / $item['weight'];
        })->first();

        $client = app($clientClass['class']);
        if (! $client instanceof AiClientInterface) {
            throw new RuntimeException('No AI Client found');
        }

        return $client;
    }

    public static function getHeavyLoadClient(): AiClientInterface
    {
        $clients = app('ai-heavy-clients');
        if (! $clients instanceof Collection) {
            throw new RuntimeException('No AI Clients available');
        }

        $clientClass = $clients->random();
        $client = app($clientClass);
        if (! $client instanceof AiClientInterface) {
            throw new RuntimeException('No AI Client found');
        }

        return $client;
    }
}
