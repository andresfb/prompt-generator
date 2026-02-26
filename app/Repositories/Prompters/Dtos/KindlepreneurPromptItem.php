<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Prompter\KindlepreneurItem;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class KindlepreneurPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $title,
        public string $sectionDescription,
        public string $description,
        public string $text,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            'kindlepreneur-prompt-view',
            KindlepreneurItem::class,
        );
    }

    public function toMarkdown(): string
    {
        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionDescription** ")
            ->append(PHP_EOL)
            ->append(sprintf(
                '<p><small>%s</small></p>', nl2br($this->description)
            ))
            ->append(PHP_EOL.PHP_EOL)
            ->append("### $this->subHeader")
            ->append(PHP_EOL)
            ->append($this->text)
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
