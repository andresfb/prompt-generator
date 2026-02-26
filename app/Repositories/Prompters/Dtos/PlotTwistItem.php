<?php

namespace App\Repositories\Prompters\Dtos;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * @param Collection<SectionItem>|string|null $roll
 */
class PlotTwistItem extends Data
{
    public function __construct(
        public string $text,
        public string $description = '',
        public string $rollType = '',
        public Collection|string|null $roll = null,
    ) {}

    public function withRoll(string $type, Collection|string $roll): self
    {
        return new self(
            $this->text,
            $this->description,
            $type,
            $roll,
        );
    }
}
