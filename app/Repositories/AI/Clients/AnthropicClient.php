<?php

declare(strict_types=1);

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Prism\Prism\Enums\Provider as ProviderEnum;

final class AnthropicClient extends BaseAiClient
{
    public function __construct()
    {
        $this->model = Config::string('anthropic.model');
        $this->maxTokens = Config::integer('anthropic.max_tokens');
    }

    public function getName(): string
    {
        return 'Anthropic';
    }

    public function getProvider(): ProviderEnum
    {
        return ProviderEnum::Anthropic;
    }
}
