<?php

declare(strict_types=1);

namespace App\Models;

use App\Libraries\MediaNamesLibrary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $movie_mashup_prompt_id
 * @property string $movie_id
 * @property string $title
 * @property ?string $year
 * @property ?string $image_type
 * @property ?string $image_tag
 * @property ?string $overview
 * @property ?array $genres
 */
final class MovieMashupItem extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $guarded = [];

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(MovieMashupPrompt::class);
    }

    public function info(): BelongsTo
    {
        return $this->belongsTo(MovieInfo::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaNamesLibrary::thumbnail())
            ->withResponsiveImages()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/avif',
            ])->singleFile();
    }

    protected function casts(): array
    {
        return [
            'genres' => 'json',
        ];
    }
}
