<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Libraries;

use App\Repositories\APIs\Dtos\StudioRequestItem;
use App\Repositories\APIs\Dtos\StudioRespondItem;
use App\Repositories\APIs\Dtos\StudioRespondSceneItem;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class MediaStudioApiLibrary
{
    /**
     * @throws Exception
     */
    public function getStudioScenes(StudioRequestItem $request): StudioRespondItem
    {
        $apiKey = Config::string('media-studios.api-key');

        try {
            $response = Http::withToken($apiKey)
                ->accept('application/json')
                ->get($request->endPoint, [
                    'page' => $request->page,
                    'per_page' => $request->perPage,
                ])
                ->throw();

            $result = $response->json();
            if (! is_array($result)) {
                throw new RuntimeException('Invalid response: missing data');
            }

            return new StudioRespondItem(
                uuid: $request->uuid,
                total: $result['meta']['total'],
                current_page: (int) $result['meta']['current_page'],
                last_page: (int) $result['meta']['last_page'],
                scenes: $this->loadScenes($result['data']),
            );
        } catch (Exception $e) {
            Log::error('@MediaStudioApiLibrary.getStudioScenes: '.$e->getMessage());

            throw $e;
        }
    }

    /**
     * @return Collection<int, StudioRespondSceneItem|null>
    */
    private function loadScenes(array $data): Collection
    {
        if (blank($data)) {
            return collect();
        }

        return collect($data)->map(function (array $scene): ?StudioRespondSceneItem {
            if (blank($scene['description'])) {
                return null;
            }

            $tags = isset($scene['tags']) && is_array($scene['tags'])
                ? collect($scene['tags'])->pluck('name')->toArray()
                : [];

            return new StudioRespondSceneItem(
                id: $scene['id'],
                slug: $scene['slug'],
                title: $scene['title'],
                description: $scene['description'] ?? '',
                tags: $tags,
                image: $scene['image'] ?? null,
                trailer: $scene['trailer'] ?? null,
                published_at: $scene['date'] !== null
                    ? CarbonImmutable::parse($scene['date'])
                    : null,
            );
        })
        ->reject(fn(?StudioRespondSceneItem $sceneItem): bool => !$sceneItem instanceof StudioRespondSceneItem);
    }
}
