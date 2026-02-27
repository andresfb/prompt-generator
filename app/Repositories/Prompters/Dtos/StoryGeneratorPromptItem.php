<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Prompter\StoryGeneratorItem;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class StoryGeneratorPromptItem extends BasePromptItem
{
    public function __construct(
        public array $modelIds,
        public string $title,
        public string $header,
        public string $sectionSituation,
        public string $situation,
        public string $sectionCharacter,
        public string $character,
        public string $sectionAction,
        public string $action,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            'story-generator-prompt-view',
            StoryGeneratorItem::class,
        );
    }

    public function toMarkdown(): string
    {
        return str("# $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionSituation:** ")
            ->append($this->situation)
            ->append(PHP_EOL)
            ->append("**$this->sectionCharacter:** ")
            ->append($this->character)
            ->append(PHP_EOL)
            ->append("**$this->sectionAction:** ")
            ->append($this->action)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
