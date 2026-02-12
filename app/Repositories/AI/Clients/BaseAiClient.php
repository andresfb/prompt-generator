<?php

namespace App\Repositories\AI\Clients;

use App\Repositories\AI\Dtos\AiChatResponse;
use App\Repositories\AI\Interfaces\AiClientInterface;
use Prism\Prism\Enums\Provider as ProviderEnum;
use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Messages\SystemMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use RuntimeException;

abstract class BaseAiClient implements AiClientInterface
{
    protected string $caller = '';

    protected string $origin = '';

    protected int $maxTokens;

    protected string $agentPrompt = '';

    protected string $userPrompt = '';

    protected string $model;

    protected array $providerConfig = [];

    protected array $clientOptions = [];

    abstract public function getName(): string;

    abstract public function getProvider(): string|ProviderEnum;

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

        return $this;
    }

    public function getUserPrompt(): string
    {
        return $this->userPrompt;
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
                $this->getProvider(),
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
