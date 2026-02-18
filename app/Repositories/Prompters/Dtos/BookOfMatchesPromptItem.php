<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Spatie\LaravelData\Data;

class BookOfMatchesPromptItem extends Data implements PromptItemInterface
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $text,
        public string $modifiers,
        public string $view = '',
        public string $resource = '',
    ) {}

    public function toMarkdown(): string
    {
        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->subHeader")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->text)
            ->append(PHP_EOL)
            ->append($this->modifiers)
            ->trim()
            ->append(PHP_EOL);
    }
}
