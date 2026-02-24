<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Boogie\StoryIdea;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class StoryIdeaPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $sectionGenre,
        public string $genre,
        public string $sectionTone,
        public string $tone,
        public string $sectionIdea,
        public string $idea,
        public ?string $sectionSubGenre = null,
        public ?string $subGenre = null,
        public string $view = '',
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            $this->view,
            StoryIdea::class,
        );
    }

    public function toMarkdown(): string
    {
        $subGenre = str('');
        if (! blank($this->subGenre)) {
            $subGenre = $subGenre->append("**$this->sectionSubGenre:** ")
                ->append($this->subGenre)
                ->append(PHP_EOL.PHP_EOL);
        }

        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->subHeader")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionGenre:** ")
            ->append($this->genre)
            ->append(PHP_EOL.PHP_EOL)
            ->append($subGenre->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionTone:** ")
            ->append($this->tone)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionIdea:** ")
            ->append(PHP_EOL)
            ->append($this->idea)
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
