<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;

class MovieCollectionPromptItem extends BasePromptItem
{
    public function __construct(
        public int $modelId,
        public string $header,
        public string $subHeader,
        public string $sectionTitle,
        public string $title,
        public string $year,
        public string $url,
        public string $sectionOverview,
        public string $overview,
        public string $sectionImage,
        public string $image,
        public ?string $sectionTagLines = null,
        public ?array $tagLines = null,
        public ?string $sectionGenres = null,
        public ?array $genres = null,
        public ?string $sectionTrailers = null,
        public ?array $trailers = null,
        public string $view = '',
        public ?ModifierPromptItem $modifiers,
    ) {
        parent::__construct($view);
    }

    public function toMarkdown(): string
    {
        $tagLines = str('');
        if (! blank($this->tagLines)) {
            $tagLines = $tagLines->append("**$this->sectionTagLines:**")
                ->append(PHP_EOL);

            foreach ($this->tagLines as $tagLine) {
                $tagLines = $tagLines->append($tagLine)
                    ->append(PHP_EOL);
            }
        }

        $genres = str('');
        if (! blank($this->genres)) {
            $genres = $genres->append("**$this->sectionGenres:**")
                ->append(PHP_EOL);

            foreach ($this->genres as $genre) {
                $genres = $genres->append($genre)
                    ->append(PHP_EOL);
            }
        }

        $trailers = str('');
        if (! blank($this->trailers)) {
            $trailers = $trailers->append(PHP_EOL)
                ->append("**$this->sectionTrailers:**")
                ->append(PHP_EOL);

            $i = 1;
            foreach ($this->trailers as $trailer) {
                $trailers = $trailers->append("![Trailer $i]($trailer)")
                    ->append(PHP_EOL);

                $i++;
            }
        }

        return str("# $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append("### $this->subHeader")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionTitle:** ")
            ->append("[$this->title ($this->year)]($this->url)")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->sectionOverview:** ")
            ->append(PHP_EOL)
            ->append($this->overview)
            ->append(PHP_EOL.PHP_EOL)
            ->append($tagLines->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append($genres->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append("![$this->sectionImage]($this->image)")
            ->append(PHP_EOL.PHP_EOL)
            ->append($trailers->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL);
    }
}
