<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class PulpAdventurePromptItem extends BasePromptItem
{
    public function __construct(
        public array $modelIds,
        public string $title,
        public string $header,
        public string $villan,
        public string $plot,
        public string $mainLocation,
        public string $sectionAct1,
        public string $act1HockElements,
        public string $act1SupportCharacters,
        public string $act1ActionSequence,
        public string $act1PlotTwist,
        public string $sectionAct2,
        public string $act2ActionSequence,
        public string $act2PlotTwist,
        public string $sectionAct3,
        public string $act3ActionSequence,
        public string $act3PlotTwist,
        public string $view = '',
        public ?ModifierPromptItem $modifiers,
    ) {
        parent::__construct($view);
    }

    public function toJson($options = 0): string
    {
        return strip_tags(
            parent::toJson()
        );
    }

    public function toMarkdown(): string
    {
        return str("# Pulp Adventure Prompts")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## Prompt")
            ->append(PHP_EOL)
            ->append($this->villan)
            ->append(PHP_EOL)
            ->append($this->plot)
            ->append(PHP_EOL)
            ->append($this->mainLocation)
            ->append(PHP_EOL.PHP_EOL)
            ->append("#### $this->sectionAct1")
            ->append(PHP_EOL)
            ->append($this->act1HockElements)
            ->append(PHP_EOL)
            ->append($this->act1SupportCharacters)
            ->append(PHP_EOL)
            ->append($this->act1ActionSequence)
            ->append(PHP_EOL)
            ->append($this->act1PlotTwist)
            ->append(PHP_EOL.PHP_EOL)
            ->append("#### $this->sectionAct2")
            ->append(PHP_EOL)
            ->append($this->act2ActionSequence)
            ->append(PHP_EOL)
            ->append($this->act2PlotTwist)
            ->append(PHP_EOL.PHP_EOL)
            ->append("#### $this->sectionAct3")
            ->append(PHP_EOL)
            ->append($this->act3ActionSequence)
            ->append(PHP_EOL)
            ->append($this->act3PlotTwist)
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
