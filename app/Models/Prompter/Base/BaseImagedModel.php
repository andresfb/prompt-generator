<?php

declare(strict_types=1);

namespace App\Models\Prompter\Base;

use App\Libraries\MediaNamesLibrary;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $movie_id
 * @property string $title
 * @property ?string $image_type
 * @property ?string $image_tag
 */
abstract class BaseImagedModel extends Model implements HasMedia
{
    use InteractsWithMedia;

    final public function getMediaName(): string
    {
        return MediaNamesLibrary::primary();
    }

    final public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->getMediaName())
            ->withResponsiveImages()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/avif',
            ])->singleFile();
    }
}
