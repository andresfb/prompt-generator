<?php

declare(strict_types=1);

namespace App\Repositories\AI\Factories;

use App\Repositories\AI\Interfaces\AiClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
        $clientsKey = 'ai-heavy-clients';

        $clients = app($clientsKey);
        if (! $clients instanceof Collection) {
            throw new RuntimeException('No AI Clients available');
        }

        $usedClients = Cache::store('apc')->array($clientsKey, []);
        if (count($usedClients) >= $clients->count()) {
            Cache::forget($clientsKey);
            $usedClients = [];
        }

        do {
            $clientClass = $clients->random();
        } while (in_array($clientClass, $usedClients, true));

        $client = app($clientClass);
        if (! $client instanceof AiClientInterface) {
            throw new RuntimeException('No AI Client found');
        }

        $usedClients[] = $clientClass;
        Cache::store('apc')->put($clientsKey, $usedClients, now()->addDay());

        return $client;
    }
}
