<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;
use Parsedown;

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
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            'short-story-outline-prompt-view',
            $this->model,
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
        $html = str((new Parsedown())->text(nl2br($this->outline)));

        return $html->replace('<h1>', '<h1 class="sm:text-2xl text-xl font-semibold title-font mb-4">')
            ->replace('<h2>', '<h2 class="sm:text-xl text-lg font-medium title-font mb-3">')
            ->replace('<h3>', '<h3 class="sm:text-lg text-base font-medium title-font mb-3">')
            ->replace('<ul>', '<ul class="list-disc list-inside mb-4">')
            ->replace('<li>', '<li class="mb-2 py-1">')
            ->trim()
            ->toString();
    }
}
