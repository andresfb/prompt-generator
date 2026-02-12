<?php

namespace App\Console\Commands;

use App\Repositories\Search\RefreshMovieMashupService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class RefreshMovieMashupCommand extends Command
{
    protected $signature = 'refresh:mashup';

    protected $description = 'Refresh the Movie Mashup database';

    public function handle(RefreshMovieMashupService $service): void
    {
        try {
            clear();
            intro('Refreshing Movie Mashup database');

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
