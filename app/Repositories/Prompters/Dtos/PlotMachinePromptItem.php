<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class PlotMachinePromptItem extends BasePromptItem
{
    public function __construct(
        public array $modelIds,
        public string $title,
        public string $header,
        public string $sectionSetting,
        public string $setting,
        public string $sectionActOfVillan,
        public string $actOfVillan,
        public string $sectionMotive,
        public string $motive,
        public string $sectionComplicater,
        public string $complicater,
        public string $sectionTwists,
        public string $twists,
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
            ->append("**$this->sectionSetting:** ")
            ->append($this->setting)
            ->append(PHP_EOL)
            ->append("**$this->sectionActOfVillan:** ")
            ->append($this->actOfVillan)
            ->append(PHP_EOL)
            ->append("**$this->sectionMotive:** ")
            ->append($this->motive)
            ->append(PHP_EOL)
            ->append("**$this->sectionComplicater:** ")
            ->append($this->complicater)
            ->append(PHP_EOL)
            ->append("**$this->sectionTwists:** ")
            ->append($this->twists)
            ->append(PHP_EOL)
            ->append($this->modifiers)
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
