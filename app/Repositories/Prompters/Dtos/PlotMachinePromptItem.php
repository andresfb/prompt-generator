<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Prompter\PlotMachinePrompt;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class PlotMachinePromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $title,
        public string $sectionGenre,
        public string $genre,
        public string $sectionSetting,
        public string $setting,
        public string $sectionActOfVillan,
        public string $actOfVillan,
        public string $sectionMotive,
        public string $motive,
        public string $sectionComplicater,
        public string $complicater,
        public string $sectionTwist,
        public string $twist,
        public string $sectionPrompt,
        public string $prompt,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            'plot-machine-prompt-view',
            PlotMachinePrompt::class,
        );
    }

    public function toMarkdown(): string
    {
        return str("# $this->title")
            ->newLine(2)
            ->append("**$this->sectionGenre:** ")
            ->append($this->genre)
            ->newLine()
            ->append("**$this->sectionSetting:** ")
            ->append($this->setting)
            ->newLine()
            ->append("**$this->sectionActOfVillan:** ")
            ->append($this->actOfVillan)
            ->newLine()
            ->append("**$this->sectionMotive:** ")
            ->append($this->motive)
            ->newLine()
            ->append("**$this->sectionComplicater:** ")
            ->append($this->complicater)
            ->newLine()
            ->append("**$this->sectionTwist:** ")
            ->append($this->twist)
            ->newLine(2)
            ->append("### $this->sectionPrompt:")
            ->newLine()
            ->append($this->prompt)
            ->newLine()
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->newLine()
            ->toString();
    }
}
