<?php

namespace App\Console\Commands;

use App\Repositories\APIs\Services\RedditPromptsStarterService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class RedditPromptsStarterCommand extends Command
{
    protected $signature = 'reddit:starter';

    protected $description = 'Start the process to import the Reddit Writing Prompts';

    public function handle(RedditPromptsStarterService $service): void
    {
        try {
            clear();
            intro('Starting Reddit Writing Prompts Import');

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
