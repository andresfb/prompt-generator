<?php

declare(strict_types=1);

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
    protected string $title = '';

    protected string $service = '';

    protected int $maxTokens;

    protected string $agentPrompt = '';

    protected string $userPrompt = '';

    protected string $clientName = '';

    protected string $model;

    protected array $providerConfig = [];

    protected array $clientOptions = [];

    abstract public function getName(): string;

    abstract public function getProvider(): string|ProviderEnum;

    final public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    final public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }

    final public function setAgentPrompt(string $agentPrompt): self
    {
        $this->agentPrompt = $agentPrompt;

        return $this;
    }

    final public function getAgentPrompt(): string
    {
        if (blank($this->userPrompt)) {
            throw new RuntimeException('Cannot use Agent Prompt without User Prompt');
        }

        return $this->agentPrompt;
    }

    final public function setUserPrompt(string $userPrompt): self
    {
        $this->userPrompt = $userPrompt;

        return $this;
    }

    final public function getUserPrompt(): string
    {
        return $this->userPrompt;
    }

    final public function setMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }

    final public function setProviderConfig(array $providerConfig): self
    {
        $this->providerConfig = $providerConfig;

        return $this;
    }

    final public function setClientOptions(array $clientOptions): self
    {
        $this->clientOptions = $clientOptions;

        return $this;
    }

    public function setClientName(string $client): self
    {
        $this->clientName = $client;
        return $this;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    final public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    final public function getModel(): string
    {
        return $this->model;
    }

    final public function ask(): AiChatResponse
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
            $this->service,
            $this->clientName,
            $this->model,
            $this->title,
        );
    }

    protected function getMessageOptions(): array
    {
        return ['cacheType' => 'ephemeral'];
    }
}
