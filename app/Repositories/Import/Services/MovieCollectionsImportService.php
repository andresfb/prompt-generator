<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\MovieCollection;
use App\Repositories\APIs\Libraries\EmbyApiLibrary;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Exception;
use Illuminate\Support\Facades\Config;
use stdClass;

final class MovieCollectionsImportService extends BaseImporterService
{
    public function __construct(private readonly EmbyApiLibrary $apiLibrary)
    {
        parent::__construct();
    }

    public function getName(): string
    {
        return 'Movie Collections';
    }

    /**
     * @throws Exception
     */
    protected function execute(): void
    {
        $collectionIds = Config::array('emby.collections');
        $collections = $this->apiLibrary->getCollections();
        if (blank($collections)) {
            $this->error('No collections found');

            return;
        }

        /** @var stdClass $collection */
        foreach ($collections as $collection) {
            if (! in_array($collection->Id, $collectionIds, true)) {
                continue;
            }

            if (MovieCollection::where('item_id', $collection->Id)->exists()) {
                continue;
            }

            MovieCollection::create([
                'item_id' => $collection->Id,
                'name' => $collection->Name,
                'genres' => $collection->Genres,
            ]);

            $this->character('.');
        }

    }
}
