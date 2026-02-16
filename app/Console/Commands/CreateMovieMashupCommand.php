<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Search\Services\CreateMovieMashupService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class CreateMovieMashupCommand extends Command
{
    protected $signature = 'create:mashup';

    protected $description = 'Creates Movie Mashups';

    public function handle(CreateMovieMashupService $service): void
    {
        try {
            clear();
            intro('Creating Movie Mashups');

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
