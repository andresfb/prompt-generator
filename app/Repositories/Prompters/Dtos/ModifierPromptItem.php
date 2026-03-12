<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;
use Override;

final class ModifierPromptItem extends BasePromptItem
{
    public function __construct(
        public string $caller,
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
    ) {
        parent::__construct($this->caller);
    }

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

    #[Override]
    protected function getCleanData(): array
    {
        $data = parent::getCleanData();

        unset($data['modifiers']);

        if (! $this->anachronise) {
            unset($data['anachronise'], $data['anachroniseText']);
        }

        return $data;
    }
}
