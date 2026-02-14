<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Prompter\MovieMashupPrompt;
use App\Repositories\AI\Services\GenerateMovieMashupPromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class GenerateMovieMashupPromptCommand extends Command
{
    protected $signature = 'generate:mashup';

    protected $description = 'Generates a Movie Mashup Prompt using AI';

    public function handle(GenerateMovieMashupPromptService $service): void
    {
        try {
            clear();
            intro('Generating a Movie Mashup Prompt');

            $promptId = $service->setToScreen(true)->execute();

            if ($promptId === 0) {
                return;
            }

            $prompt = MovieMashupPrompt::findOrFail($promptId);

            /** @noinspection ForgottenDebugOutputInspection */
            dump($prompt);

            $this->newLine();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
