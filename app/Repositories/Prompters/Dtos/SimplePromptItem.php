<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class SimplePromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $text,
        public string $view = '',
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct($view);
    }

    public function toMarkdown(): string
    {
        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->subHeader")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->text)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL);
    }
}
