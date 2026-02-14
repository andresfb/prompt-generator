<?php

namespace App\Repositories\APIs\Dtos;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Data;

class RedditResponseItem extends Data
{
    public function __construct(
        public int $id,
        public string $hash,
        public string $title,
        public string $permalink,
        public CarbonInterface $published_at,
    ) {}
}
