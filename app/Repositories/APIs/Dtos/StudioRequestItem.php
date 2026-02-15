<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Dtos;

use Spatie\LaravelData\Data;

final class StudioRequestItem extends Data
{
    public function __construct(
        public string $uuid,
        public string $endPoint,
        public int $page = 1,
        public int $perPage = 25,
    ) {}
}
