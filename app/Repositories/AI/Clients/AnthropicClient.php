<?php

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Enums\Provider as ProviderEnum;

class AnthropicClient extends BaseAiClient
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

    public function getProvider(): string|ProviderEnum
    {
        return Provider::Anthropic;
    }
}
