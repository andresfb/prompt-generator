<?php

declare(strict_types=1);

namespace App\Repositories\AI\Dtos;

use Spatie\LaravelData\Data;

final class PromptSettingItem extends Data
{
    public function __construct(
        public string $genre = '',
        public string $setting = '',
        public string $character = '',
        public string $conflict = '',
        public string $tone = '',
        public string $narrative = '',
        public string $period = '',
    ) {}
}
