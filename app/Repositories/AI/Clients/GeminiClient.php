<?php

declare(strict_types=1);

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Prism\Prism\Enums\Provider as ProviderEnum;

final class GeminiClient extends BaseAiClient
{
    public function __construct()
    {
        $this->model = Config::string('gemini.model');
        $this->maxTokens = Config::integer('gemini.max_tokens');
    }

    public function getName(): string
    {
        return 'Gemini';
    }

    public function getProvider(): string|ProviderEnum
    {
        return ProviderEnum::Gemini;
    }
}
