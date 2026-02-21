<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class NovelStarterPromptItem extends BasePromptItem
{
    public function __construct(
        public array $modelIds,
        public string $title,
        public string $header,
        public string $sectionHero,
        public string $hero,
        public string $sectionFlaws,
        public string $flaws,
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
            ->append("**$this->sectionHero:** ")
            ->append($this->hero)
            ->append(PHP_EOL)
            ->append("**$this->sectionFlaws:** ")
            ->append($this->flaws)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
