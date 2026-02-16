<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Prompter\RedditPromptEndpoint;
use App\Repositories\APIs\Services\RedditWritingPromptsService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class RedditWritingPromptsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly RedditPromptEndpoint $endpoint)
    {
        $this->queue = 'worker';
        $this->delay = now()->addSeconds(15);
    }

    /**
     * @throws Exception
     */
    public function handle(RedditWritingPromptsService $service): void
    {
        try {
            $service->execute($this->endpoint);
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
