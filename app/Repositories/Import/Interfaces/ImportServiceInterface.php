<?php

declare(strict_types=1);

namespace App\Repositories\Import\Interfaces;

interface ImportServiceInterface
{
    public function import(): void;

    public function getName(): string;

    public function setToScreen(bool $toScreen): self;
}
