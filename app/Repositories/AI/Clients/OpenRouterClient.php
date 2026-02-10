<?php

namespace App\Repositories\AI\Clients;

use App\Repositories\AI\Dtos\AiChatResponse;
use App\Repositories\AI\Interfaces\AiClientInterface;
use Illuminate\Support\Facades\Config;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Messages\SystemMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use RuntimeException;

class OpenRouterClient implements AiClientInterface
{
    public function __construct()
    {
        $this->model = Config::string('open-router.model');
        $this->maxTokens = Config::integer('open-router.max_tokens');
    }

    private string $caller = '';

    private string $origin = '';

    private int $maxTokens;

    private string $agentPrompt = '';

    private string $userPrompt = '';

    private string $model;

    private array $providerConfig = [];

    private array $clientOptions = [];

    public function setCaller(string $caller): AiClientInterface
    {
        $this->caller = $caller;

        return $this;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function setAgentPrompt(string $agentPrompt): self
    {
        $this->agentPrompt = $agentPrompt;

        return $this;
    }

    public function getAgentPrompt(): string
    {
        if (blank($this->userPrompt)) {
            throw new RuntimeException('Cannot use Agent Prompt without User Prompt');
        }

        return $this->agentPrompt;
    }

    public function setUserPrompt(string $userPrompt): self
    {
        $this->userPrompt = $userPrompt;

        dd($this->userPrompt);;

        return $this;
    }

    public function setMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }

    public function setProviderConfig(array $providerConfig): self
    {
        $this->providerConfig = $providerConfig;

        return $this;
    }

    public function setClientOptions(array $clientOptions): self
    {
        $this->clientOptions = $clientOptions;

        return $this;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    protected function getMessageOptions(): array
    {
        return ['cacheType' => 'ephemeral'];
    }

    public function ask(): AiChatResponse
    {
        $prism = Prism::text()
            ->using(
                Provider::OpenAI,
                $this->getModel(),
                $this->providerConfig,
            )
            ->withMaxTokens($this->maxTokens)
            ->withClientOptions(
                $this->clientOptions
            )
            ->withMessages([
                new UserMessage($this->userPrompt),
            ]);

        if (! blank($this->agentPrompt)) {
            $prism->withSystemPrompt(
                (new SystemMessage($this->getAgentPrompt()))
                    ->withProviderOptions($this->getMessageOptions()),
            );
        }

        $response = $prism->asText();

        return AiChatResponse::fromResponse(
            $response,
            $this->origin,
            $this->caller,
            self::class,
        );
    }
}
