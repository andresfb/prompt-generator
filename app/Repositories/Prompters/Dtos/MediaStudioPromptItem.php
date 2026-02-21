<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class MediaStudioPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $sectionTitle,
        public string $title,
        public string $sectionDescription,
        public string $description,
        public ?string $sectionTags = null,
        public ?array $tags = null,
        public ?string $sectionImage = null,
        public ?string $image = null,
        public ?string $sectionTrailer = null,
        public ?string $trailer = null,
        public string $view = '',
        public ?ModifierPromptItem $modifiers,
    ) {
        parent::__construct($view);
    }

    public function toMarkdown(): string
    {
        $image = str('');
        if (! blank($this->image)) {
            $image = $image->append("![$this->sectionImage]($this->image)")
                ->append(PHP_EOL);
        }

        $tags = str('');
        if (! blank($this->tags)) {
            $tags = $tags->append("**$this->sectionTags:**")
                ->append(PHP_EOL);

            $tags = $tags->append(
                collect($this->tags)->take(10)->implode(', ')
            )
            ->append(PHP_EOL);
        }

        $trailer = str('');
        if (! blank($this->trailer)) {
            $trailer = $trailer->append(PHP_EOL)
                ->append("**$this->sectionTrailer:**")
                ->append(PHP_EOL);

            $trailer = $trailer->append("![Trailer]($this->trailer)")
                ->append(PHP_EOL);
        }

        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("### $this->subHeader")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionTitle:** ")
            ->append($this->title)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionDescription:** ")
            ->append(PHP_EOL)
            ->append($this->description)
            ->append(PHP_EOL.PHP_EOL)
            ->append($tags->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append($image->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append($trailer->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL);
    }
}
