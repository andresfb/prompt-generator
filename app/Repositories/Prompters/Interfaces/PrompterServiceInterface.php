<?php

namespace App\Repositories\Prompters\Interfaces;

interface PrompterServiceInterface
{
    public function execute(): ?PromptItemInterface;

    public function setToScreen(bool $toScreen): self;
}
