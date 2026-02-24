<?php

namespace App\Jobs;

use App\Services\MarkPromptUsedService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MarkPromptUsedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $hash)
    {
        $this->queue = 'worker';
        $this->delay = now()->addSeconds(5);
    }

    /**
     * @throws Exception
     */
    public function handle(MarkPromptUsedService $service): void
    {
        try {
            $service->execute($this->hash);
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
