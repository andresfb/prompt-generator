<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\AiNotificationSummaryService;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

final class AiNotificationSummaryCommand extends Command
{
    protected $signature = 'send:ai-summary';

    protected $description = 'Send the AI usage Summary email';

    public function handle(AiNotificationSummaryService $service): void
    {
        try {
            clear();
            intro('Sending AI Summary');

            $service->execute();
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
