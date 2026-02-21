<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class KindlepreneurPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $title,
        public string $sectionDescription,
        public string $description,
        public string $text,
        public string $view = '',
        public ?ModifierPromptItem $modifiers,
    ) {
        parent::__construct($view);
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
                 "<p><small>%s</small></p>", nl2br($this->description)
             ))
             ->append(PHP_EOL.PHP_EOL)
             ->append("### $this->subHeader")
             ->append(PHP_EOL)
             ->append($this->text)
             ->append(PHP_EOL.PHP_EOL)
             ->append($this->modifiers?->toMarkdown())
             ->trim()
             ->append(PHP_EOL);
    }
}
