<?php

declare(strict_types=1);

namespace App\Repositories\Search\Services;

use App\Jobs\CreateMovieMashupJob;
use App\Models\Prompter\MovieInfo;
use App\Models\Prompter\MovieMashupItem;
use App\Models\Prompter\MovieMashupPrompt;
use App\Traits\ImageExtractor;
use App\Traits\Screenable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

final class CreateMovieMashupService
{
    use ImageExtractor;
    use Screenable;

    private int $maxMovies;

    public function __construct(private readonly RefreshMoviesService $refreshService)
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
        CreateMovieMashupJob::dispatch();
    }

    private function getMovies(): array
    {
        $this->refreshService
            ->setToScreen($this->toScreen)
            ->execute();

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

    private function savePrompt(array $items): bool
    {
        $promptId = 0;
        $saved = false;

        foreach ($items as $hash => $movies) {
            if (MovieMashupPrompt::where('hash', $hash)->exists()) {
                continue;
            }

            try {
                DB::transaction(function () use ($hash, $movies, &$promptId) {
                    $prompt = MovieMashupPrompt::create([
                        'hash' => $hash,
                    ]);

                    $promptId = $prompt->id;
                    foreach ($movies as $movie) {
                        [$image, $imageType] = $this->getImage($movie['content']);

                        MovieMashupItem::create([
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
