<?php

namespace App\Repositories\APIs\Services;

use App\Jobs\MediaStudioItemsJob;
use App\Models\Prompter\MediaStudio;
use App\Repositories\APIs\Dtos\StudioRequestItem;
use App\Traits\Screenable;

class MediaStudioStarterService
{
    use Screenable;

    public function execute(): void
    {
        $this->info('Starting Media Studio Item Import');

        $studios = MediaStudio::query()
            ->where('active', true)
            ->get();

        if ($studios->isEmpty()) {
            $this->error('No Active Media Studios found');

            return;
        }

        $studios->each(function (MediaStudio $studio) {
            MediaStudioItemsJob::dispatch(
                new StudioRequestItem(
                    uuid: $studio->uuid,
                    endPoint: $studio->endpoint,
                    page: ++$studio->current_page,
                    perPage: $studio->per_page,
                )
            );
        });

        $this->line(2);
        $this->info('Finished Media Studio Item Import');
    }
}
