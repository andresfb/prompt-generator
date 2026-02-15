<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Libraries;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class EmbyApiLibrary
{
    public const string COLLECTION_TYPE = 'collections';

    public const string COLLECTION_MOVIE_TYPE = 'collection_movies';

    private array $endPoints;

    public function __construct()
    {
        $baseUrl = Config::string('emby.api-url');

        $this->endPoints = [
            self::COLLECTION_TYPE => sprintf(
                $baseUrl,
                '/Items',
                http_build_query(
                    Config::array('emby.collection_params')
                ),
            ),
            self::COLLECTION_MOVIE_TYPE => sprintf(
                $baseUrl,
                '/Items',
                ''
            ),
        ];
    }

    /**
     * @throws Exception
     */
    public function getCollections(): array
    {
        $url = $this->endPoints[self::COLLECTION_TYPE];

        return $this->getData($url);
    }

    /**
     * @throws Exception
     */
    public function getCollectionMovies(string $collectionId): array
    {
        $url = $this->endPoints[self::COLLECTION_MOVIE_TYPE];

        $params = Config::array('emby.collection_movies_params');
        $params['ParentId'] = $collectionId;
        $query = http_build_query($params);

        return $this->getData($url.$query);
    }

    /**
     * @throws Exception
     */
    private function getData(string $url): array
    {
        try {
            $response = json_decode($this->getResponse($url), false, 512, JSON_THROW_ON_ERROR);

            if (! $response || ! $response->Items) {
                throw new RuntimeException('Invalid response');
            }

            return $response->Items;
        } catch (Exception $e) {
            Log::error('@EmbyApiLibrary.getData: '.$e->getMessage());

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function getResponse(string $url): string
    {
        return Http::accept('application/json')
            ->get($url)
            ->throw()
            ->body();
    }
}
