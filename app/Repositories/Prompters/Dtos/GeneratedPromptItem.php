<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class GeneratedPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $title,
        public string $header,
        public string $content,
        public string $subHeader,
        public string $sectionGenre,
        public string $genre,
        public string $sectionSetting,
        public string $setting,
        public string $sectionCharacter,
        public string $character,
        public string $sectionConflict,
        public string $conflict,
        public string $sectionTone,
        public string $tone,
        public string $sectionNarrative,
        public string $narrative,
        public string $sectionPeriod,
        public string $period,
        public string $sectionEnd,
        public string $endText,
        public string $modifiers,
        public string $view = '',
        public string $resource = '',
    ) {}

    public function toMarkdown(): string
    {
        return str("# $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->content)
            ->append(PHP_EOL.PHP_EOL)
            ->append("### $this->subHeader")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionGenre:** ")
            ->append($this->genre)
            ->append(PHP_EOL)
            ->append("**$this->sectionSetting:** ")
            ->append($this->setting)
            ->append(PHP_EOL)
            ->append("**$this->sectionCharacter:** ")
            ->append($this->character)
            ->append(PHP_EOL)
            ->append("**$this->sectionConflict:** ")
            ->append($this->conflict)
            ->append(PHP_EOL)
            ->append("**$this->sectionTone:** ")
            ->append($this->tone)
            ->append(PHP_EOL)
            ->append("**$this->sectionNarrative:** ")
            ->append($this->narrative)
            ->append(PHP_EOL)
            ->append("**$this->sectionPeriod:** ")
            ->append($this->period)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionEnd:** ")
            ->append($this->endText)
            ->append(PHP_EOL)
            ->append($this->modifiers)
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
