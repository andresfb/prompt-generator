<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Dtos;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class StudioRespondItem extends Data
{
    /**
     * @param  Collection<StudioRespondSceneItem> $scenes
     */
    public function __construct(
        public string $uuid,
        public int $total,
        public int $current_page = 1,
        public int $last_page = 25,
        public ?Collection $scenes = null,
    ) {}
}
