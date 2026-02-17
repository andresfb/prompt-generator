<?php

namespace App\Repositories\Prompters\Interfaces;

use App\Repositories\Prompters\Dtos\PromptItem;

interface PrompterServiceInterface
{
    public function execute(): ?PromptItem;

    public function setToScreen(bool $toScreen): self;
}
