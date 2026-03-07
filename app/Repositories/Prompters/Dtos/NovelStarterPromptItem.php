<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Prompter\NovelStarterPrompt;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class NovelStarterPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $title,
        public string $sectionGenre,
        public string $genre,
        public string $sectionHero,
        public string $hero,
        public string $sectionFlaw,
        public string $flaw,
        public string $sectionPrompt,
        public string $prompt,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            'novel-starter-prompt-view',
            NovelStarterPrompt::class,
        );
    }

    public function toMarkdown(): string
    {
        return str("# $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionGenre:** ")
            ->append($this->genre)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionHero:** ")
            ->append($this->hero)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionFlaw:** ")
            ->append($this->flaw)
            ->append(PHP_EOL.PHP_EOL)
            ->append("### $this->sectionPrompt:")
            ->append(PHP_EOL)
            ->append($this->prompt)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
