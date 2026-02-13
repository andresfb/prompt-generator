<?php

declare(strict_types=1);

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Prism\Prism\Enums\Provider as ProviderEnum;

final class OpenRouterClient extends BaseAiClient
{
    public function __construct()
    {
        $this->model = Config::string('openrouter.model');
        $this->maxTokens = Config::integer('openrouter.max_tokens');
    }

    public function getName(): string
    {
        return 'OpenRouter';
    }

    public function getProvider(): string|ProviderEnum
    {
        return ProviderEnum::OpenRouter;
    }
}
