<?php

namespace App\Console\Commands;

use App\Repositories\Search\RandomMoviesService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class TestAppCommand extends Command
{
    protected $signature = 'test:app';

    protected $description = 'Command to do random tests';

    public function handle(): void
    {
        try {
            clear();
            intro('Running tests');

            $srv = app(RandomMoviesService::class);
            $srv->execute();

        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
