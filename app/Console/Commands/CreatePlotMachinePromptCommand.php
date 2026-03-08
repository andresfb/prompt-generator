<?php

namespace App\Console\Commands;

use App\Repositories\Search\Services\CreatePlotMachinePromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class CreatePlotMachinePromptCommand extends Command
{
    protected $signature = 'create:plot-prompt';

    protected $description = 'Creates the Plot Machine Prompt';

    public function handle(CreatePlotMachinePromptService $service): void
    {
        try {
            clear();
            intro('Creating Plot Machine Prompt');

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
