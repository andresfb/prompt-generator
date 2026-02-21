<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

final class MovieMashupItem extends BasePromptItem
{
    public function __construct(
        public string $id,
        public string $title,
        public string $year,
        public string $image_type,
        public string $image_tag,
        public string $overview,
        public string $url,
        public string $image,
        public ?array $genres = null,
    ) {
        parent::__construct();
    }

    public function toMarkdown(): string
    {
        $genres = str('');
        if (! blank($this->genres)) {
            $genres = str(collect($this->genres)->implode(', '))
                ->trim()
                ->append(PHP_EOL);
        }

        return str("**[$this->title ($this->year)]($this->url)**")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->overview)
            ->append(PHP_EOL.PHP_EOL)
            ->append($genres->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append("![$this->image_type]($this->image)")
            ->append(PHP_EOL)
            ->append('***')
            ->append(PHP_EOL)
            ->toString();
    }
}
