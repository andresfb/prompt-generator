<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\AddMovieImageAction;
use App\Models\Prompter\MovieCollectionItem;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class AddMovieItemImageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly int $movieId)
    {
        $this->queue = 'media';
        $this->delay = now()->addSeconds(5);
    }

    /**
     * @throws Exception
     */
    public function handle(AddMovieImageAction $action): void
    {
        try {
            $movie = MovieCollectionItem::query()
                ->where('id', $this->movieId)
                ->firstOrFail();

            $action->handle($movie);
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
