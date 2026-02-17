<?php

namespace App\Console\Commands;

use App\Repositories\Prompters\Factories\PrompterFactory;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\error;

class GetPromptCommand extends Command
{
    protected $signature = 'get:prompt';

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

            echo str($item->text)
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
