<?php

namespace App\Repositories\AI\Dtos;

use Spatie\LaravelData\Data;

class PromptSettingItem extends Data
{
    public function __construct(
        public string $genre = '',
        public string $setting = '',
        public string $character = '',
        public string $conflict = '',
        public string $tone = '',
        public string $narrative = '',
        public string $period = '',
    ) { }
}
