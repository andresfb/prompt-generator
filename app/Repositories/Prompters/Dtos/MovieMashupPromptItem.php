<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class MovieMashupPromptItem extends Data implements PromptItemInterface
{
    /**
     * @param Collection<MovieMashupItem> $movies
     */
    public function __construct(
        public string $id,
        public string $content,
        public string $provider,
        public Collection $movies
    ) {}
}
