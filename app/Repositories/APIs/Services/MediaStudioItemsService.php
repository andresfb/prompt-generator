<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Services;

use App\Models\Prompter\MediaStudio;
use App\Models\Prompter\MediaStudioItem;
use App\Repositories\APIs\Dtos\StudioRequestItem;
use App\Repositories\APIs\Dtos\StudioRespondSceneItem;
use App\Repositories\APIs\Libraries\MediaStudioApiLibrary;
use App\Traits\Screenable;
use Exception;

final class MediaStudioItemsService
{
    use Screenable;

    public function __construct(private readonly MediaStudioApiLibrary $studioApi) {}

    public function execute(StudioRequestItem $requestItem): void
    {
        $this->info('Starting Media Studio Items Import');

        try {
            $studio = MediaStudio::query()
                ->where('uuid', $requestItem->uuid)
                ->firstOrFail();

            $this->notice('Loading data from API');

            $response = $this->studioApi->getStudioScenes($requestItem);
            if ($response->scenes->isEmpty()) {
                $this->error('No Studio Scenes found');

                return;
            }

            if ($response->total === MediaStudioItem::getCount($studio->id)) {
                $this->error('No new Studio Scenes found');

                return;
            }

            $this->notice("Found $response->total studio scenes");
            $this->notice("Saving {$response->scenes->count()} scenes");

            $response->scenes->each(function (StudioRespondSceneItem $scene) use ($studio): void {
                if (MediaStudioItem::where('uuid', $scene->id)->exists()) {
                    return;
                }

                $this->character('.');

                $sceneData = $scene->toArray();
                $sceneData['media_studio_id'] = $studio->id;
                unset($sceneData['tags']);

                $item = MediaStudioItem::create($sceneData);
                if (! blank($scene->tags)) {
                    $item->attachTags($scene->tags);
                }
            });

            $this->line();

            MediaStudio::where('id', $studio->id)
                ->update([
                    'total_scenes' => $response->total,
                    'current_page' => $response->current_page,
                    'last_page' => $response->last_page,
                ]);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        } finally {
            $this->line();
            $this->info('Finished Media Studio Items Import');
        }
    }
}
