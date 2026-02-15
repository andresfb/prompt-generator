<?php

namespace App\Console\Commands;

use App\Repositories\Search\Services\RefreshMoviesService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class RefreshMoviesCommand extends Command
{
    protected $signature = 'refresh:movies';

    protected $description = 'Import new movies from the Search index';

    public function handle(RefreshMoviesService $service): void
    {
        try {
            clear();
            intro('Refreshing movies');

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
