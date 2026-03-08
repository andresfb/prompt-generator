<?php

namespace App\Console\Commands;

use App\Models\Prompter\PlotMachinePrompt;
use App\Repositories\AI\Services\GeneratePlotMachinePromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class GeneratePlotMachinePromptCommand extends Command
{
    protected $signature = 'generate:plot-prompt';

    protected $description = 'Generates Plot Machine Prompt using AI';

    public function handle(GeneratePlotMachinePromptService $service): void
    {
        try {
            clear();
            intro('Generating a Plot Machine Prompt');

            $promptId = $service->setToScreen(true)
                ->execute();

            $prompt = PlotMachinePrompt::query()
                ->where('id', $promptId)
                ->firstOrFail();

            $this->newLine();

            /** @noinspection ForgottenDebugOutputInspection */
            print_r($prompt->only('genre', 'setting', 'act_of_villan', 'motive', 'complicater', 'twist', 'content'));

        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
