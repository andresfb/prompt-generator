<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Services;

use App\Events\UpdateMovieCollectionCountEvent;
use App\Jobs\AddMovieItemImageJob;
use App\Models\Prompter\MovieCollection;
use App\Models\Prompter\MovieCollectionItem;
use App\Models\Prompter\MovieInfo;
use App\Repositories\APIs\Libraries\EmbyApiLibrary;
use App\Traits\ImageExtractor;
use App\Traits\Screenable;
use Exception;
use stdClass;

final class MovieCollectionItemsService
{
    use ImageExtractor;
    use Screenable;

    public function __construct(private readonly EmbyApiLibrary $apiLibrary) {}

    public function execute(): void
    {
        $this->info('Starting Movie Collection Items Import');

        $collections = MovieCollection::query()
            ->where('active', true)
            ->get();

        $this->notice('Loading Collections');

        foreach ($collections as $collection) {
            try {
                $movies = $this->apiLibrary->getCollectionMovies($collection->item_id);
                if (blank($movies)) {
                    continue;
                }

                $movieCount = count($movies);
                if ($movieCount === MovieCollection::getCount($collection->item_id)) {
                    continue;
                }

                $this->notice("Importing movies for $collection->name");
                $this->importMovies($collection->id, $movies);
                $this->line();

                UpdateMovieCollectionCountEvent::dispatch($collection->id);

                usleep(500000);
            } catch (Exception $e) {
                $this->error("Movie API error for: $collection->item_id: ".$e->getMessage());

                continue;
            }
        }

        $this->line();
        $this->info('Finished Movie Collection Items Import');
    }

    private function importMovies(int $collectionId, array $movies): void
    {
        /** @var stdClass $movie */
        foreach ($movies as $movie) {
            if (MovieCollectionItem::where('movie_info_id', $movie->Id)->exists()) {
                continue;
            }

            $info = MovieInfo::query()
                ->where('movie_id', $movie->Id)
                ->first();

            if ($info === null) {
                continue;
            }

            [$image, $imageType] = $this->getImage($info->content);

            $item = MovieCollectionItem::create([
                'movie_collection_id' => $collectionId,
                'movie_info_id' => $info->id,
                'movie_id' => $info->movie_id,
                'title' => $info->content['Name'],
                'year' => $info->content['ProductionYear'] ?? null,
                'overview' => $info->content['Overview'] ?? null,
                'genres' => $info->content['Genres'] ?? null,
                'image_type' => $imageType,
                'image_tag' => $image ?: null,
                'tag_lines' => $movie->Taglines ?? null,
                'trailers' => $movie->RemoteTrailers ?? null,
            ]);

            $this->character('.');

            AddMovieItemImageJob::dispatch($item->id);
        }

        $this->line();
    }
}
