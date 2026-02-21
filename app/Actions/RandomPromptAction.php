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
        $prompter = PrompterFactory::getPrompter();
        $item = $prompter->execute();
        if (! $item instanceof PromptItemInterface) {
            throw new RuntimeException('No prompter found');
        }

        return $item;
    }
}
