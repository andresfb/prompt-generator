<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdateMovieCollectionCountEvent;
use App\Models\Prompter\MovieCollection;
use App\Models\Prompter\MovieCollectionItem;
use Illuminate\Contracts\Queue\ShouldQueue;

final class UpdateMovieCollectionCountListener implements ShouldQueue
{
    public string $queue = 'worker';

    public function handle(UpdateMovieCollectionCountEvent $event): void
    {
        MovieCollection::query()
            ->where('id', $event->collectionId)
            ->update([
                'count' => MovieCollectionItem::where('movie_collection_id', $event->collectionId)->count(),
            ]);
    }
}
