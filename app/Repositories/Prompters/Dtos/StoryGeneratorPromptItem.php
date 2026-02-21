<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class StoryGeneratorPromptItem extends BasePromptItem
{
    public function __construct(
        public array $modelIds,
        public string $title,
        public string $header,
        public string $sectionSituations,
        public string $situations,
        public string $sectionCharacters,
        public string $characters,
        public string $sectionActions,
        public string $actions,
        public string $view = '',
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct($view);
    }

    public function toMarkdown(): string
    {
        return str("# $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionSituations:** ")
            ->append($this->situations)
            ->append(PHP_EOL)
            ->append("**$this->sectionCharacters:** ")
            ->append($this->characters)
            ->append(PHP_EOL)
            ->append("**$this->sectionActions:** ")
            ->append($this->actions)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
