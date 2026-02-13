<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\AI\Services\GeneratePromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class GeneratePromptCommand extends Command
{
    protected $signature = 'generate:prompt';

    protected $description = 'Generates Creative Writing Prompt using AI';

    public function handle(GeneratePromptService $service): void
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
                    '%s:  %s',
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
