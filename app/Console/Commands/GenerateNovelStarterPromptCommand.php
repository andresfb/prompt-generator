<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Prompter\NovelStarterPrompt;
use App\Repositories\AI\Services\GenerateNovelStarterPromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class GenerateNovelStarterPromptCommand extends Command
{
    protected $signature = 'generate:starter-prompt';

    protected $description = 'Generates Novel Starter Prompt using AI';

    public function handle(GenerateNovelStarterPromptService $service): void
    {
        try {
            clear();
            intro('Generating a Novel Starter Prompt');

            $promptId = $service->setToScreen(true)
                ->execute();

            $prompt = NovelStarterPrompt::query()
                ->where('id', $promptId)
                ->firstOrFail();

            $this->newLine();

            /** @noinspection ForgottenDebugOutputInspection */
            print_r($prompt->only('genre', 'hero', 'flaw', 'content'));

        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
