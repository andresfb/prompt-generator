<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class RedditPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $title,
        public string $permalink,
        public string $view = '',
        public string $resource = '',
        public ?ModifierPromptItem $modifiers,
    ) {}

    public function toMarkdown(): string
    {
        return str($this->header)
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->title)
            ->append(PHP_EOL.PHP_EOL)
            ->append("![Perma Link]({$this->permalink})")
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL);
    }
}
