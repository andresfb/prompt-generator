<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\APIs\Services\MovieCollectionItemsService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class ImportMovieCollectionItemsCommand extends Command
{
    protected $signature = 'movie:items';

    protected $description = 'Import the Movie Collection Items';

    public function handle(MovieCollectionItemsService $service): void
    {
        try {
            clear();
            intro('Importing Movie Collection Items');

            $service->setToScreen(true)
                ->execute();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
