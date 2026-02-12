<?php

namespace App\Repositories\Search;

use App\Models\MovieInfo;
use App\Models\MovieMashupItem;
use App\Models\MovieMashupPrompt;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Meilisearch\Client;
use Meilisearch\Contracts\DocumentsQuery;
use RuntimeException;
use Throwable;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

// TODO: add a Screenable trait to display the messages when the service is run in the CLI. Otherwise send it to the Log.
class RandomMoviesService
{
    private int $maxMovies;

    private int $maxChunks = 500;

    public function __construct()
    {
        $this->maxMovies = Config::integer('constants.max_mashup_movies');
    }

    public function execute(): void
    {
        $items = $this->getMovies();

        info('Creating Prompt records');

        [$saved, $promptId] = $this->savePrompt($items);

        if (! $saved) {
            // todo: redispatch the job that calls this service

            warning('No Movie Mashup Prompts Saved');

            return;
        }

        // todo: dispatch the job to generate the AI summary for this new prompt
    }

    private function getMovies(): array
    {
        $this->refreshMovies();

        $data = MovieInfo::query()
            ->where('usages', '<', Config::integer('constants.max_mashup_movie_usages'))
            ->inRandomOrder()
            ->limit(40)
            ->get();

        if ($data->isEmpty()) {
            throw new RuntimeException('No movies found');
        }

        $movies = [];
        $data->chunk($this->maxMovies)
            ->each(function (Collection $chunk) use (&$movies) {
                $hashItems = '';
                foreach ($chunk as $item) {
                    $hashItems .= $item['content']['Id'];
                }

                $hash = md5($hashItems);
                $movies[$hash] = $chunk->toArray();
            });

        return $movies;
    }

    private function refreshMovies(): void
    {
        $movies = $this->loadMovies();
        if ($movies->isEmpty()) {
            return;
        }

        info('Saving movies');

        $movies->chunk($this->maxChunks)
            ->each(function (Collection $chunk) {
                $chunk->each(function (array $movie) {
                    MovieInfo::updateOrCreate([
                        'movie_id' => $movie['Id'],
                    ],[
                        'content' => $movie,
                    ]);

                    echo '.';
                });
            });

        echo PHP_EOL . PHP_EOL;
    }

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
                warning(sprintf("%sNo movie refresh needed.%s", PHP_EOL, PHP_EOL));

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

                echo '.';
            } while (count($documents) === $limit);

            echo PHP_EOL . PHP_EOL;

            return $allDocuments;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return collect();
        }
    }

    private function savePrompt(array $items): array
    {
        $promptId = 0;
        $saved = false;

        foreach ($items as $hash => $movies) {
            if (MovieMashupPrompt::where('hash', $hash)->exists()) {
                continue;
            }

            try {
                DB::transaction(static function () use ($hash, $movies, &$promptId) {
                    $prompt = MovieMashupPrompt::create([
                        'hash' => $hash,
                    ]);

                    $promptId = $prompt->id;
                    foreach ($movies as $movie) {
                        $imageTags = $movie['content']['ImageTags'] ?? [];
                        $image = array_shift($imageTags);

                        $item = MovieMashupItem::create([
                            'movie_mashup_prompt_id' => $promptId,
                            'movie_info_id' => $movie['id'],
                            'movie_id' => $movie['content']['Id'],
                            'title' => $movie['content']['Name'],
                            'year' => $movie['content']['ProductionYear'] ?? null,
                            'overview' => $movie['content']['Overview'] ?? null,
                            'genres' => $movie['content']['Genres'] ?? null,
                            'image_tag' => $image ?: null,
                        ]);

                        if (blank($image)) {
                            continue;
                        }

                        // todo: dispatch a job to add the image to the Medial Library
                    }
                });

                $saved = true;
            } catch (Throwable $e) {
                error($e->getMessage());
            }

            if ($saved) {
                break;
            }
        }

        return [$saved, $promptId];
    }
}
