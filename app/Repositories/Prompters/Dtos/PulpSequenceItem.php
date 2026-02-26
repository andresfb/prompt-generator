<?php

namespace App\Repositories\Prompters\Dtos;

use Spatie\LaravelData\Data;

class PulpSequenceItem extends Data
{
    public function __construct(
        public string $typeTitle,
        public string $type,
        public string $participantsTitle,
        public string $participants,
        public string $settingTitle,
        public string $setting,
        public string $complicationsTitle,
        public string $complications,
        public string $complicationsDescription = '',
    ) {}
}
