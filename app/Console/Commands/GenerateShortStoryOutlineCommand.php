<?php

namespace App\Console\Commands;

use App\Repositories\AI\Services\ShortStoryOutlineService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class GenerateShortStoryOutlineCommand extends Command
{
    protected $signature = 'generate:outline';

    protected $description = 'Generate a Short Story Outline';

    public function handle(ShortStoryOutlineService $service): void
    {
        try {
            clear();
            intro('Starting Command');

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
