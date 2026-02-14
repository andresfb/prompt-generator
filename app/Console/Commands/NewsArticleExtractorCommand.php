<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Extract\Services\NewsArticleExtractorService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class NewsArticleExtractorCommand extends Command
{
    protected $signature = 'extract:articles';

    protected $description = 'Extract articles from news source';

    public function handle(NewsArticleExtractorService $service): void
    {
        try {
            clear();
            intro('Extract Articles from the Newsroom database');

            $service->setToScreen(true)
                ->execute();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
