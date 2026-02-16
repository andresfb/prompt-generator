<?php

declare(strict_types=1);

namespace App\Repositories\Search\Services;

use App\Models\Prompter\MovieInfo;
use App\Traits\Screenable;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Meilisearch\Client;
use Meilisearch\Contracts\DocumentsQuery;

final class RefreshMoviesService
{
    use Screenable;

    private int $maxChunks = 500;

    public function execute(): void
    {
        try {
            $movies = $this->loadMovies();
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return;
        }

        if ($movies->isEmpty()) {
            return;
        }

        $this->info('Saving movies');

        $movies->chunk($this->maxChunks)
            ->each(function (Collection $chunk) {
                $chunk->each(function (array $movie) {
                    MovieInfo::updateOrCreate([
                        'movie_id' => $movie['Id'],
                    ], [
                        'content' => $movie,
                    ]);

                    $this->character('.');
                });
            });

        $this->line(2);
    }

    /**
     * @throws Exception
     */
    private function loadMovies(): Collection
    {
        info('Loading movies');

        try {
            $client = new Client(
                Config::string('meilisearch.host'),
                Config::string('meilisearch.key'),
            );

            $index = $client->index(
                Config::string('meilisearch.movies_index'),
            );

            $stats = $index->stats();
            $total = $stats['numberOfDocuments'];

            if ($total === MovieInfo::count()) {
                $this->warning(sprintf('%sNo movie refresh needed.%s', PHP_EOL, PHP_EOL));

                return collect();
            }

            $limit = $this->maxChunks;
            $offset = 0;
            $allDocuments = collect();

            do {
                $limits = new DocumentsQuery;
                $limits->setLimit($limit);
                $limits->setOffset($offset);

                $response = $index->getDocuments($limits);
                $documents = $response->getResults();

                $allDocuments = $allDocuments->merge($documents);
                $offset += $limit;

                $this->character('.');
            } while (count($documents) === $limit);

            $this->line(2);

            return $allDocuments;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return collect();
        }
    }
}
