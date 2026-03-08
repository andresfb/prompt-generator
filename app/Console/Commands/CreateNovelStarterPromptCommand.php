<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Search\Services\CreateNovelStarterPromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class CreateNovelStarterPromptCommand extends Command
{
    protected $signature = 'create:starter-prompt';

    protected $description = 'Creates the Novel Starter Prompt';

    public function handle(CreateNovelStarterPromptService $service): void
    {
        try {
            clear();
            intro('Creating Novel Starter Prompt');

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
