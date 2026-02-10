<?php

namespace App\Console\Commands;

use App\Repositories\AI\Services\CreatePromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class CreatePromptCommand extends Command
{
    protected $signature = 'create:prompt';

    protected $description = 'Creates and saves a Creative Writing Prompt using AI';

    public function handle(CreatePromptService $service): void
    {
        try {
            clear();
            intro('Generating a Creatinve Writing Prompt');

            $prompt = $service->execute();

            dump($prompt->toArray());
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
