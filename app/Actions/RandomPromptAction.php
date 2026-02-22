<?php

declare(strict_types=1);

namespace App\Actions;

use App\Repositories\Prompters\Factories\PrompterFactory;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use RuntimeException;

final readonly class RandomPromptAction
{
    public function handle(): PromptItemInterface
    {
        $runs = 0;
        $item = null;
        $maxRuns = PrompterFactory::servicesCount() * 2;

        while ($item === null && $runs++ <= $maxRuns) {
            $prompter = PrompterFactory::getPrompter();

            $item = $prompter->execute();
            if (! $item instanceof PromptItemInterface) {
                continue;
            }

            return $item;
        }

        throw new RuntimeException('No Prompts found');
    }
}
