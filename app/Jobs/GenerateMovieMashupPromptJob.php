<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Repositories\AI\Services\GenerateMovieMashupPromptService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Exceptions\PrismProviderOverloadedException;

final class GenerateMovieMashupPromptJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this->queue = 'ai-generator';
    }

    /**
     * @throws Exception
     */
    public function handle(GenerateMovieMashupPromptService $service): void
    {
        try {
            $service->execute();
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($e instanceof PrismProviderOverloadedException) {
                self::dispatch()
                    ->delay(now()->addMinutes(5));
            } else {
                throw $e;
            }
        }
    }
}
