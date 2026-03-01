<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class UpdateMovieCollectionCountEvent
{
    use Dispatchable;

    public function __construct(public int $collectionId) {}
}
