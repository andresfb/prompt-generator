<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Newsroom\Article;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class MarkNewsArticleReadJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly array $articles)
    {
        $this->queue = 'worker';
        $this->delay = now()->addSeconds(5);
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            Article::markRead($this->articles);
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
