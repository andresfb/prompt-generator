<?php

declare(strict_types=1);

namespace App\Repositories\AI\Interfaces;

use App\Repositories\AI\Dtos\AiChatResponse;

interface AiClientInterface
{
    public function setCaller(string $caller): self;

    public function setOrigin(string $origin): self;

    public function setAgentPrompt(string $agentPrompt): self;

    public function setUserPrompt(string $userPrompt): self;

    public function getUserPrompt(): string;

    public function setMaxTokens(int $maxTokens): self;

    public function setProviderConfig(array $providerConfig): self;

    public function setClientOptions(array $clientOptions): self;

    public function setModel(string $model): self;

    public function getModel(): string;

    public function getName(): string;

    public function ask(): AiChatResponse;
}
