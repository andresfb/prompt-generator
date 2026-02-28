<?php

declare(strict_types=1);

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Override;
use Prism\Prism\Enums\Provider as ProviderEnum;

final class OllamaClient extends BaseAiClient
{
    public function __construct()
    {
        $this->maxTokens = Config::integer('ollama.max_tokens');
        $this->clientOptions = [
            'timeout' => 180, // 3 minutes
        ];
    }

    public function getName(): string
    {
        return 'Ollama';
    }

    public function getProvider(): ProviderEnum
    {
        return ProviderEnum::Ollama;
    }

    #[Override]
    public function getModel(): string
    {
        $this->model = (string) collect(Config::array('ollama.models'))->random();

        return $this->model;
    }
}
