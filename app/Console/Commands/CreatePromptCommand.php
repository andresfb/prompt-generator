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
            intro('Generating a Creative Writing Prompt');

            $prompt = $service->execute();
            $response = $prompt->toArray();

            $skipFields = [
                'id',
                'prompt',
                'updated_at',
                'created_at',
            ];

            foreach ($response as $key => $item) {
                if (in_array($key, $skipFields, true)) {
                    continue;
                }

                $this->line(sprintf(
                    "%s:  %s",
                    ucwords($key),
                    $item
                ));
            }

            $this->newLine();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
