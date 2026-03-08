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
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionGenre:** ")
            ->append($this->genre)
            ->append(PHP_EOL)
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
            ->append("**$this->sectionTwist:** ")
            ->append($this->twist)
            ->append(PHP_EOL.PHP_EOL)
            ->append("### $this->sectionPrompt:")
            ->append(PHP_EOL)
            ->append($this->prompt)
            ->append(PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
