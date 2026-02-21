<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class StoryMachinePromptItem extends BasePromptItem
{
    public function __construct(
        public array $modelIds,
        public string $title,
        public string $header,
        public string $sectionConflicts,
        public string $conflicts,
        public string $sectionSettings,
        public string $settings,
        public string $sectionSubgenres,
        public string $subgenres,
        public string $sectionRandomItems,
        public string $randomItems,
        public string $sectionRandomWords,
        public string $randomWords,
        public string $sectionMustFeature,
        public string $mustFeature,
        public string $sectionMustAlsoFeature,
        public string $mustAlsoFeature,
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
            ->append("**$this->sectionConflicts:** ")
            ->append($this->conflicts)
            ->append(PHP_EOL)
            ->append("**$this->sectionSettings:** ")
            ->append($this->settings)
            ->append(PHP_EOL)
            ->append("**$this->sectionSubgenres:** ")
            ->append($this->subgenres)
            ->append(PHP_EOL)
            ->append("**$this->sectionRandomItems:** ")
            ->append($this->randomItems)
            ->append(PHP_EOL)
            ->append("**$this->sectionRandomWords:** ")
            ->append($this->randomWords)
            ->append(PHP_EOL)
            ->append("**$this->sectionMustFeature:** ")
            ->append($this->mustFeature)
            ->append(PHP_EOL)
            ->append("**$this->sectionMustAlsoFeature:** ")
            ->append($this->mustAlsoFeature)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
