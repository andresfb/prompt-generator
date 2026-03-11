<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Prompter\RedditWritingPrompt;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;
use Override;

final class RedditPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $title,
        public string $permalink,
        public string $caller,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            $this->caller,
            'reddit-prompt-view',
            RedditWritingPrompt::class,
        );
    }

    public function toMarkdown(): string
    {
        return str($this->header)
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->title)
            ->append(PHP_EOL.PHP_EOL)
            ->append("[Perma Link]({$this->permalink})")
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }

    public function getHtmlTitle(): string
    {
        return str($this->title)
            ->replace('**[', '<span class="font-medium">')
            ->replace(']**', '</span> ')
            ->toString();
    }

    #[Override]
    public function toMcp($options = 0): string
    {
        $this->permalink = '';

        return parent::toMcp($options);
    }
}
