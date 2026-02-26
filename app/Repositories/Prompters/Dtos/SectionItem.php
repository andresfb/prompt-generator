<?php

namespace App\Repositories\Prompters\Dtos;

use Spatie\LaravelData\Data;

class SectionItem extends Data
{
    public function __construct(
        public string $text,
        public string $description = '',
    ) {}

    public function withDescription(string $description): self
    {
        return new static($this->text, $description);
    }
}
