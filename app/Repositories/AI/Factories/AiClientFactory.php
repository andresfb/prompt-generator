<?php

declare(strict_types=1);

namespace App\Repositories\AI\Factories;

use App\Repositories\AI\Interfaces\AiClientInterface;
use Illuminate\Support\Collection;
use RuntimeException;

final class AiClientFactory
{
    /**
     * Get an AI Client at random from the container
     */
    public static function getClient(string $clientKey = 'ai-clients'): AiClientInterface
    {
        $clients = app($clientKey);
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
