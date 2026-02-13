<?php

namespace App\Jobs;

use App\Libraries\MediaNamesLibrary;
use App\Models\Prompter\NewsArticlePrompt;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddNewsPromptImageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly int $newsId)
    {
        $this->queue = 'media';
        $this->delay = now()->addSeconds(10);
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $news = NewsArticlePrompt::query()
                ->where('id', $this->newsId)
                ->firstOrFail();

            $news->addMediaFromUrl($news->thumbnail)
                ->toMediaCollection(MediaNamesLibrary::thumbnail());
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
