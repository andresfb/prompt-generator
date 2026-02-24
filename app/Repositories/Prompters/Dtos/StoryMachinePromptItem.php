<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Prompter\StoryMachineItem;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class StoryMachinePromptItem extends BasePromptItem
{
    public function __construct(
        public array  $modelIds,
        public string $title,
        public string $header,
        public string $sectionConflict,
        public string $conflict,
        public string $sectionSetting,
        public string $setting,
        public string $sectionSubgenre,
        public string $subgenre,
        public string $sectionRandomItem,
        public string $randomItem,
        public string $sectionRandomWord,
        public string $randomWord,
        public string $sectionMustFeature,
        public string $mustFeature,
        public string $sectionMustAlsoFeature,
        public string $mustAlsoFeature,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            'story-machine-prompt-view',
            StoryMachineItem::class,
        );
    }

    public function toMarkdown(): string
    {
        return str("# $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionConflict:** ")
            ->append($this->conflict)
            ->append(PHP_EOL)
            ->append("**$this->sectionSetting:** ")
            ->append($this->setting)
            ->append(PHP_EOL)
            ->append("**$this->sectionSubgenre:** ")
            ->append($this->subgenre)
            ->append(PHP_EOL)
            ->append("**$this->sectionRandomItem:** ")
            ->append($this->randomItem)
            ->append(PHP_EOL)
            ->append("**$this->sectionRandomWord:** ")
            ->append($this->randomWord)
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
