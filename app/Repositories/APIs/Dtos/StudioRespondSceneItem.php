<?php

namespace App\Repositories\APIs\Dtos;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Data;

class StudioRespondSceneItem extends Data
{
    public function __construct(
        public string $id,
        public string $slug,
        public string $title,
        public string $description,
        public array $tags = [],
        public ?string $image = null,
        public ?string $trailer = null,
        public ?CarbonInterface $published_at = null,
    ) {}

    public function toArray(): array
    {
        $info = parent::toArray();
        $info['uuid'] = $this->id;
        unset($info['id']);

        return $info;
    }
}
