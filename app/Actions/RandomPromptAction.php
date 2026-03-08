<?php

declare(strict_types=1);

namespace App\Actions;

use App\Repositories\Prompters\Factories\PrompterFactory;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

final readonly class RandomPromptAction
{
    public function handle(string $prompterKey = '', bool $forMcp = false): PromptItemInterface
    {
        $runs = 0;
        $item = null;
        $maxRuns = PrompterFactory::servicesCount() * 2;

        while (! $item instanceof PromptItemInterface && $runs++ <= $maxRuns) {
            $prompter = $forMcp
                ? PrompterFactory::getPrompterExcluded()
                : PrompterFactory::getPrompter($prompterKey);

            if ($prompter === null) {
                continue;
            }

            $item = $prompter->execute();
            if (! $item instanceof PromptItemInterface) {
                continue;
            }

            Cache::put($item->hash(), $item, now()->addHour());

            return $item;
        }

        throw new RuntimeException('No Prompts found');
    }
}
