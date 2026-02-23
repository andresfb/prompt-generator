<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class NewsArticlePromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $sectionSource,
        public string $source,
        public string $sectionTitle,
        public string $title,
        public string $permalink,
        public string $content,
        public string $view = '',
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct($this->view);
    }

    public function toMarkdown(): string
    {
        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->sectionSource")
            ->append(PHP_EOL)
            ->append("**$this->source**")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionTitle**")
            ->append(PHP_EOL)
            ->append($this->title)
            ->append(PHP_EOL)
            ->append("[Perma Link]($this->permalink)")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->content)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
