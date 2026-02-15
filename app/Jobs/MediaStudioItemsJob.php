<?php

namespace App\Jobs;

use App\Repositories\APIs\Dtos\StudioRequestItem;
use App\Repositories\APIs\Services\MediaStudioItemsService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MediaStudioItemsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly StudioRequestItem $requestItem)
    {
        $this->queue = 'worker';
        $this->delay = now()->addSeconds(20);
    }

    /**
     * @throws Exception
     */
    public function handle(MediaStudioItemsService $service): void
    {
        try {
            $service->execute($this->requestItem);
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
