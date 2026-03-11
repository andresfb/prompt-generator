<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\RandomPromptAction;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\error;

final class GetPromptCommand extends Command
{
    protected $signature = 'get:prompt
                            {--p|prompter= : Request a specific Prompter}
                            {--f|format= : Output data in JSON, HTML, or Markdown (MD) format}';

    protected $description = 'Selects a random Prompt using one of all the available Datasets';

    public function handle(RandomPromptAction $action): void
    {
        try {
            $ptr = $this->option('prompter') ?? '';
            $format = $this->option('format') ?? 'json';
            $format = mb_strtolower(trim($format));

            $item = $action->handle(
                prompterKey: $ptr,
                forMcp: $format === 'mcp'
            );

            if ($format === 'json') {
                echo $item->toJson();

                return;
            }

            if ($format === 'html') {
                echo $item->toHtml();

                return;
            }

            if ($format === 'mcp') {
                $data = json_decode($item->toMcp(), true, 512, JSON_THROW_ON_ERROR);
                $data['title'] = $item->getTitle();
                $data['file'] = json_decode($item->getFile(), true, 512, JSON_THROW_ON_ERROR);

                echo json_encode($data, JSON_THROW_ON_ERROR);

                return;
            }

            echo str($item->toMarkdown())
                ->replace('###', '#')
                ->replace('##', '#')
                ->trim()
                ->toString();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
        }
    }
}
