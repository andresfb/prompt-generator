<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Services;

use App\Jobs\RedditWritingPromptsJob;
use App\Models\Prompter\RedditPromptEndpoint;
use App\Traits\Screenable;

final class RedditPromptsStarterService
{
    use Screenable;

    public function execute(): void
    {
        $this->info('Starting Reddit Writing Prompts Import');

        $endpoints = RedditPromptEndpoint::query()
            ->where('active', true)
            ->get();

        if ($endpoints->isEmpty()) {
            $this->error('No Reddit Prompt Endpoints Found');

            return;
        }

        $endpoints->each(function (RedditPromptEndpoint $endpoint): void {
            RedditWritingPromptsJob::dispatch($endpoint);
            $this->character('.');
        });

        $this->line(2);
        $this->info('Finished Reddit Writing Prompts Import');
    }
}
