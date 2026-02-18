<?php

declare(strict_types=1);

namespace App\Repositories\Extract\Services;

use App\Jobs\MarkNewsArticleReadJob;
use App\Models\Newsroom\Article;
use App\Models\Prompter\NewsArticlePrompt;
use App\Traits\Screenable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

final class NewsArticleExtractorService
{
    use Screenable;

    private int $maxArticles;

    public function __construct()
    {
        $this->maxArticles = Config::integer('news-articles.max_source_news');
    }

    public function execute(): void
    {
        $this->info('Starting importing News Article Prompts');

        $sources = $this->getNewsSources();
        if ($sources->isEmpty()) {
            $this->error('No news sources found');

            return;
        }

        $saved = [];
        $savedCount = 0;

        $this->info('Creating the News Prompts');

        /** @var Article $source */
        foreach ($sources as $source) {
            if ($savedCount >= $this->maxArticles) {
                break;
            }

            if (NewsArticlePrompt::where('source_id', $source->id)->exists()) {
                continue;
            }

            NewsArticlePrompt::create([
                'source_id' => $source->id,
                'source' => $this->getSource($source->permalink),
                'title' => $source->title,
                'content' => $this->getContent($source),
                'permalink' => $source->permalink,
                'thumbnail' => $source->thumbnail,
                'published_at' => $source->published_at,
            ]);

            $savedCount++;
            $saved[] = $source->id;

            $this->character('.');
        }

        MarkNewsArticleReadJob::dispatch($saved);

        $this->line(2);
        $this->info('Finished importing News Article Prompts');
    }

    private function getNewsSources(): Collection
    {
        $this->info('Loading Articles from the source');

        $list = Article::query()
            ->pending()
            ->inRandomOrder()
            ->limit($this->maxArticles * 5)
            ->get();

        $this->warning("Loaded {$list->count()} Articles");

        return $list;
    }

    /**
     * Get the shorter text
     */
    private function getContent(Article $source): string
    {
        $contentLength = mb_strlen($source->content);
        $descriptionLength = mb_strlen($source->description);

        if ($contentLength > $descriptionLength) {
            return $source->description;
        }

        return $source->content;
    }

    private function getSource(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (! $host) {
            return '';
        }

        $host = preg_replace('/^www\./', '', $host);
        $parts = explode('.', $host);
        $count = count($parts);

        if ($count >= 2) {
            return $parts[$count - 2].'.'.$parts[$count - 1];
        }

        return $host;
    }
}
