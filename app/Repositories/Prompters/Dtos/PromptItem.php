<?php

namespace App\Repositories\Prompters\Dtos;

use Spatie\LaravelData\Data;

class PromptItem extends Data
{
    public function __construct(
        public string $text = '',
        public string $view = '',
        public string $resource = '',
    ) {}
}
