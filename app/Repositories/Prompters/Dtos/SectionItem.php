<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use Spatie\LaravelData\Data;

final class SectionItem extends Data
{
    public function __construct(
        public string $text,
        public string $description = '',
    ) {}

    public function withDescription(string $description): self
    {
        return new self($this->text, $description);
    }
}
