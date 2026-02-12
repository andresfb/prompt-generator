<?php

namespace App\Repositories\Search;

use App\Jobs\AddMovieMashupImageJob;
use App\Jobs\RefreshMoviesMashupJob;
use App\Models\MovieInfo;
use App\Models\MovieMashupItem;
use App\Models\MovieMashupPrompt;
use App\Traits\Screenable;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Meilisearch\Client;
use Meilisearch\Contracts\DocumentsQuery;
use RuntimeException;
use Throwable;

class RefreshMovieMashupService
{
    use Screenable;

    private int $maxMovies;

    private int $maxChunks = 500;

    public function __construct()
    {
        $this->maxMovies = count(
            Config::array('movie-mashups.mashup_settings')
        );
    }

    public function execute(): void
    {
        $items = $this->getMovies();

        $this->info('Creating Prompt records');

        $saved = $this->savePrompt($items);
        if ($saved) {
            return;
        }

        $this->warning('No Movie Mashup Prompts Saved. Re-queuing the job');
        RefreshMoviesMashupJob::dispatch();
    }

    private function getMovies(): array
    {
        $this->refreshMovies();

        $maxUsages = Config::integer('movie-mashups.max_mashup_movie_usages');
        $data = MovieInfo::query()
            ->where('usages', '<=', $maxUsages)
            ->inRandomOrder()
            ->limit($this->maxMovies * $maxUsages)
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

        $this->info('Saving movies');

        $movies->chunk($this->maxChunks)
            ->each(function (Collection $chunk) {
                $chunk->each(function (array $movie) {
                    MovieInfo::updateOrCreate([
                        'movie_id' => $movie['Id'],
                    ],[
                        'content' => $movie,
                    ]);

                    $this->character('.');
                });
            });

        $this->line(2);
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
                $this->warning(sprintf("%sNo movie refresh needed.%s", PHP_EOL, PHP_EOL));

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

    private function savePrompt(array $items): bool
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
                        $image = '';
                        $imageType = '';

                        $imageTags = $movie['content']['ImageTags'] ?? ['' => ''];
                        foreach ($imageTags as $type => $imageTag) {
                            if ($type !== 'Primary') {
                                continue;
                            }

                            $imageType = $type;
                            $image = $imageTag;

                            break;
                        }

                        $item = MovieMashupItem::create([
                            'movie_mashup_prompt_id' => $promptId,
                            'movie_info_id' => $movie['id'],
                            'movie_id' => $movie['content']['Id'],
                            'title' => $movie['content']['Name'],
                            'year' => $movie['content']['ProductionYear'] ?? null,
                            'overview' => $movie['content']['Overview'] ?? null,
                            'genres' => $movie['content']['Genres'] ?? null,
                            'image_type' => $imageType,
                            'image_tag' => $image ?: null,
                        ]);

                        if (blank($image)) {
                            continue;
                        }

                        MovieInfo::where('movie_id', $movie['id'])
                            ->increment('usages');

                        AddMovieMashupImageJob::dispatch($item->id);
                    }
                });

                $saved = true;
            } catch (Throwable $e) {
                $this->error($e->getMessage());
            }

            if ($saved) {
                break;
            }
        }

        return $saved;
    }
}
