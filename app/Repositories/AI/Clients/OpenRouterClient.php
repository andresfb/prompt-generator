<?php

declare(strict_types=1);

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Override;
use Prism\Prism\Enums\Provider as ProviderEnum;

final class OpenRouterClient extends BaseAiClient
{
    public function __construct()
    {
        $this->model = '';
        $this->maxTokens = Config::integer('openrouter.max_tokens');
    }

    public function getName(): string
    {
        return 'OpenRouter';
    }

    public function getProvider(): ProviderEnum
    {
        return ProviderEnum::OpenRouter;
    }

    #[Override]
    public function getModel(): string
    {
        if (! blank($this->model)) {
            return $this->model;
        }

        $this->model = (string) collect(Config::array('openrouter.models'))->random();

        return $this->model;
    }

    #[Override]
    public function setLightModel(): self
    {
        $this->model = Config::string('openrouter.light_model');

        return $this;
    }
}
