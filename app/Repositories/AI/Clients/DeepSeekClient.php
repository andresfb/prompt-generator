<?php

declare(strict_types=1);

namespace App\Repositories\AI\Clients;

use Illuminate\Support\Facades\Config;
use Override;
use Prism\Prism\Enums\Provider as ProviderEnum;

final class DeepSeekClient extends BaseAiClient
{
    public function __construct()
    {
        $this->model = Config::string('deepseek.model');
        $this->maxTokens = Config::integer('deepseek.max_tokens');
    }

    public function getName(): string
    {
        return 'DeepSeek';
    }

    public function getProvider(): ProviderEnum
    {
        return ProviderEnum::DeepSeek;
    }

    #[Override]
    public function setLightModel(): self
    {
        $this->model = Config::string('deepseek.light_model');

        return $this;
    }
}
