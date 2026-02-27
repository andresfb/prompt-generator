<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Prompter\MediaStudio;
use App\Repositories\APIs\Dtos\StudioRequestItem;
use App\Repositories\APIs\Services\MediaStudioItemsService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;
use function Laravel\Prompts\warning;

final class GetMediaStudioItemsCommand extends Command
{
    protected $signature = 'studio:items';

    protected $description = 'Get the next set of of Media Studio Items';

    public function handle(MediaStudioItemsService $service): void
    {
        try {
            clear();
            intro('Getting Studio Items');

            $studios = MediaStudio::query()
                ->where('active', true)
                ->get();

            $studio = select(
                label: 'Select a Studio',
                options: $studios->pluck('name', 'uuid')->toArray(),
                default: $studios->first()->name,
                scroll: 10,
            );

            $studio = $studios->firstWhere('uuid', $studio);

            warning("Fetching Items for $studio->name from API");

            $service->setToScreen(true)
                ->execute(
                    new StudioRequestItem(
                        uuid: $studio->uuid,
                        endPoint: $studio->endpoint,
                        page: ++$studio->current_page,
                        perPage: $studio->per_page,
                    )
                );

            $this->newLine();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
