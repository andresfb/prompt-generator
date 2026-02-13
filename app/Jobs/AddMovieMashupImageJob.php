<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Libraries\MediaNamesLibrary;
use App\Models\MovieMashupItem;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class AddMovieMashupImageJob implements ShouldQueue
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
    public function handle(): void
    {
        try {
            $movie = MovieMashupItem::query()
                ->where('id', $this->movieId)
                ->firstOrFail();

            if (blank($movie->image_tag)) {
                throw new RuntimeException("Movie Item $movie->id | $movie->title doesn't have image tag");
            }

            $imgUrl = sprintf(
                Config::string('emby.image_url'),
                $movie->movie_id,
                $movie->image_type,
                $movie->image_tag
            );

            $movie->addMediaFromUrl($imgUrl)
                ->toMediaCollection(MediaNamesLibrary::thumbnail());
        } catch (MaxAttemptsExceededException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw $e;
        }
    }
}
