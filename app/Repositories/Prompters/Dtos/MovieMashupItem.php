<?php

namespace App\Repositories\Prompters\Dtos;

use Spatie\LaravelData\Data;

class MovieMashupItem extends Data
{
    public function __construct(
        public string $id,
        public string $title,
        public string $year,
        public string $image_type,
        public string $image_tag,
        public string $overview,
        public array $genres = [],
    ) {}
}
