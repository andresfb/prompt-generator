<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\MarkPromptUsedJob;

final readonly class MarkPromptUsedAction
{
    public function handle(string $promptHash): void
    {
        MarkPromptUsedJob::dispatch($promptHash);
    }
}
