<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class TitledPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header = '',
        public string $subHeader = '',
        public string $title = '',
        public string $text = '',
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
            ->append("**$this->title**")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->text)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
