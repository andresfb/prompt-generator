<?php

namespace App\Console\Commands;

use App\Models\MovieMashupPrompt;
use App\Repositories\AI\Services\GenerateMovieMashupPromptService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class GenerateMovieMashupPromptCommand extends Command
{
    protected $signature = 'generate:mashup';

    protected $description = 'Generates a Movie Mashup Prompt using AI';

    public function handle(GenerateMovieMashupPromptService $service): void
    {
        try {
            clear();
            intro('Generating a Movie Mashup Prompt');

            $mashup = MovieMashupPrompt::query()
                ->where('generated', false)
                ->firstOrFail();

            $prompt = $service->execute($mashup->id);
            $response = $prompt->toArray();

            $skipFields = [
                'id',
                'hash',
                'active',
                'generated',
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
