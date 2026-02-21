<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Interfaces;

interface PrompterServiceInterface
{
    public function execute(): ?PromptItemInterface;

    public function setToScreen(bool $toScreen): self;
}
