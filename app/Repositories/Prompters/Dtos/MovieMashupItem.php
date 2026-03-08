<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;
use Illuminate\Support\Facades\Config;

final class MovieMashupItem extends BasePromptItem
{
    public function __construct(
        public string $id,
        public string $movie_id,
        public string $title,
        public string $year,
        public string $overview,
        public string $url,
        public ?array $genres = null,
        public ?array $images = null,
        public ?string $used_for = null,
    ) {
        parent::__construct();
    }

    public function toMarkdown(): string
    {
        $genres = str('');
        if (! blank($this->genres)) {
            $genres = str(collect($this->genres)->implode(', '))
                ->trim()
                ->newLine();
        }

        $usedFor = str('');
        if (! blank($this->used_for)) {
            $usedFor = $usedFor->append('**Used For**')
                ->newLine()
                ->append($this->used_for);
        }

        $images = str('');
        [$type, $image] = $this->getImage();
        if (! blank($type)) {
            $type = $type ?: 'image';
            $images = $images->append("![$type]({$this->getImageUrl($type, $image)}");
        }

        return str("**[$this->title ($this->year)]($this->url)**")
            ->newLine(2)
            ->append($usedFor->trim()->toString())
            ->trim()
            ->newLine(2)
            ->append($this->overview)
            ->newLine(2)
            ->append($genres->trim()->toString())
            ->trim()
            ->newLine(2)
            ->append($images->trim()->toString())
            ->trim()
            ->newLine(2)
            ->append('***')
            ->newLine()
            ->toString();
    }

    public function getImage(string $type = 'Primary'): array
    {
        $noImage = Config::string('constants.missing_cover_image');

        if (blank($this->images)) {
            return ['', $noImage];
        }

        if (! array_key_exists($type, $this->images)) {
            return ['', $noImage];
        }

        return [$type, $this->images[$type]];
    }

    public function getThumbnail(): string
    {
        [$type, $image] = $this->getImage('Thumb');
        if (blank($type)) {
            [$type, $image] = $this->getImage();
        }

        if (blank($image)) {
            return Config::string('constants.missing_cover_image');
        }

        return $this->getImageUrl($type, $image);
    }

    private function getImageUrl(string $type, string $image): string
    {
        return sprintf(
            Config::string('emby.image_url'),
            $this->movie_id,
            $type,
            $image
        );
    }
}
