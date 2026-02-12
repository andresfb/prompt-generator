<?php

namespace App\Jobs;

use App\Repositories\AI\Services\GeneratePromptService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GeneratePromptJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this->queue = 'ai-generator';
        $this->delay = now()->addSeconds(5);
    }

    /**
     * @throws Exception
     */
    public function handle(GeneratePromptService $service): void
    {
        try {
            $service->execute();
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        }catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
