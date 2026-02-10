<?php

namespace App\Repositories\AI\Factories;

use App\Repositories\AI\Interfaces\AiClientInterface;
use Illuminate\Support\Collection;
use RuntimeException;

class AiClientFactory
{
    /**
     * Get an AI Client at random from the container
     */
    public static function getClient(): AiClientInterface
    {
        $clients = app('ai-clients');
        if (! $clients instanceof Collection) {
            throw new RuntimeException('No AI Clients available');
        }

        $client = $clients->random();
        if (! $client instanceof AiClientInterface) {
            throw new RuntimeException('No AI Client found');
        }

        return $client;
    }
}
