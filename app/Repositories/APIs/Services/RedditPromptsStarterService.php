<?php

declare(strict_types=1);

namespace App\Repositories\APIs\Services;

use App\Jobs\RedditWritingPromptsJob;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class RedditPromptsStarterService
{
    use Screenable;

    private array $endpoints;

    public function __construct()
    {
        $this->endpoints = Config::array('reddit-prompts.endpoints');
    }

    public function execute(): void
    {
        $this->info('Starting Reddit Writing Prompts Import');

        foreach ($this->endpoints as $endpoint) {
            RedditWritingPromptsJob::dispatch($endpoint);
            $this->character('.');
        }

        $this->line(2);
        $this->info('Finished Reddit Writing Prompts Import');
    }
}
