<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class ModifierPromptItem extends BasePromptItem
{
    public function __construct(
        public string $title = '',
        public string $sectionAge = '',
        public string $age = '',
        public string $sectionDescendancy = '',
        public string $descendancy = '',
        public string $sectionGender = '',
        public string $gender = '',
        public string $sectionPointOfView = '',
        public string $pointOfView = '',
        public string $sectionTimePeriods = '',
        public string $timePeriods = '',
        public bool $anachronise = false,
        public string $anachroniseText = 'USE ANACHRONISTIC LANGUAGE',
    ) {}

    public function toMarkdown(): string
    {
        $anachronistic = $this->anachronise
            ? "**$this->anachroniseText**"
            : '';

        return str("### $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionAge:** ")
            ->append($this->age)
            ->append(PHP_EOL)
            ->append("**$this->sectionDescendancy:** ")
            ->append($this->descendancy)
            ->append(PHP_EOL)
            ->append("**$this->sectionGender:** ")
            ->append($this->gender)
            ->append(PHP_EOL)
            ->append("**$this->sectionPointOfView:** ")
            ->append($this->pointOfView)
            ->append(PHP_EOL)
            ->append("**$this->sectionTimePeriods:** ")
            ->append($this->timePeriods)
            ->append(PHP_EOL.PHP_EOL)
            ->append($anachronistic)
            ->trim()
            ->toString();
    }
}
