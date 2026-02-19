<?php

namespace App\Console\Commands;

use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Factories\PrompterFactory;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\error;

class GetPromptCommand extends Command
{
    protected $signature = 'get:prompt {--f|format= : Output data in JSON, HTML, or Markdown (MD) format}';

    protected $description = 'Selects a random Prompt using one of all the available Datasets';

    public function handle(): void
    {
        try {
            $prompter = PrompterFactory::getPrompter();
            $item = $prompter->execute();
            if ($item === null) {
                error('No prompter found');

                return;
            }

            $format = $this->option('format') ?? 'json';
            $format = strtolower(trim($format));

            if ($format === 'json') {
                echo $item->toJson();

                return;
            }

            if ($format === 'html') {
                echo $item->toHtml();

                return;
            }

            echo $item->toMarkdown();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
        }
    }

    private function showPrompt(PromptItem $item): void
    {
        $hint = str('');
        if (! blank($item->hint)) {
            $hint = $hint->append(PHP_EOL)
                ->append('# Hint')
                ->append(PHP_EOL)
                ->append($item->hint)
                ->append(PHP_EOL);
        }

        $image = str('');
        if (! blank($item->image)) {
            $image = $image->append(PHP_EOL)
                ->append("![Image]($item->image)")
                ->append(PHP_EOL);
        }

        $trailers = str('');
        if (! blank($item->trailers)) {
            $trailers = $trailers->append(PHP_EOL)
                ->append('**Trailers**')
                ->append(PHP_EOL);

            $i = 1;
            foreach ($item->trailers as $trailer) {
                $trailers = $trailers->append("![Trailer $i]($trailer)")
                    ->append(PHP_EOL);

                $i++;
            }
        }

        echo str($item->text)
            ->replace('{{HINT}}', '')
            ->append(PHP_EOL)
            ->append($hint->trim()->toString())
            ->append(PHP_EOL)
            ->append($image->trim()->toString())
            ->append(PHP_EOL)
            ->append($trailers->toString())
            ->append(PHP_EOL)
            ->replace('###', '#')
            ->replace('##', '#')
            ->trim()
            ->toString();
    }
}
