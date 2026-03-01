<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class SimplePromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $text,
        public string $model,
        public string $image = '',
        public string $responsive = '',
        public string $view = '',
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            $this->view ?: 'simple-prompt-view',
            $this->model,
        );
    }

    public function toMarkdown(): string
    {
        $image = str('');
        if (! blank($this->image)) {
            $image = $image->append("![Thumbnail]($this->image)");
        }

        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->subHeader")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->text)
            ->append(PHP_EOL.PHP_EOL)
            ->append($image->trim()->toString())
            ->append(PHP_EOL.PHP_EOL)
            ->trim()
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
