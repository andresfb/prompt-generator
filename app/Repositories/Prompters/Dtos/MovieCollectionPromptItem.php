<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Models\Prompter\MovieCollectionItem;
use App\Repositories\Prompters\Dtos\Base\BasePromptItem;
use Illuminate\Support\Facades\Config;

final class MovieCollectionPromptItem extends BasePromptItem
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
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct(
            'movie-collection-prompt-view',
            MovieCollectionItem::class,
        );
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
            ->append(PHP_EOL)
            ->toString();
    }

    public function embeddedTrailer(string $url): string
    {
        $videoId = '';
        $youtubeSI = Config::string('constants.youtube_si');
        $embeddedUrl = Config::string('constants.youtube_embed_url');

        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['host'])) {
            return '';
        }

        // Short URL format: youtu.be/{id}
        if ($parsedUrl['host'] === 'youtu.be') {
            $videoId = ltrim($parsedUrl['path'], '/');
        }

        // Standard YouTube URL
        if (blank($videoId) && str_contains($parsedUrl['host'], 'youtube.com')) {
            parse_str($parsedUrl['query'] ?? '', $queryParams);
            $videoId = $queryParams['v'] ?? '';
        }

        if (blank($videoId)) {
            return '';
        }

        return sprintf(
            $embeddedUrl,
            $videoId,
            $youtubeSI,
        );
    }
}
