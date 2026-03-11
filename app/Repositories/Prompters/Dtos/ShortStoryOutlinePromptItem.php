<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class ShortStoryOutlinePromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $genreTitle,
        public string $genre,
        public string $outline,
        public string $provider,
        public string $model,
        public string $caller,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            $this->caller,
            'short-story-outline-prompt-view',
            $this->model,
            $this->modifiers,
        );
    }

    public function toMarkdown(): string
    {
        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**Genre:** $this->genre")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->outline)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }

    public function getOutlineHtml(): string
    {
        return $this->getHtml($this->outline);
    }
}
