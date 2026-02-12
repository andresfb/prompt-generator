<?php

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Prism\Prism\Enums\Provider as ProviderEnum;

class OpenAiClient extends BaseAiClient
{
    public function __construct()
    {
        $this->model = Config::string('openai.model');
        $this->maxTokens = Config::integer('openai.max_tokens');
    }

    public function getName(): string
    {
        return 'OpenAI';
    }

    public function getProvider(): string|ProviderEnum
    {
        return ProviderEnum::OpenAI;
    }
}
