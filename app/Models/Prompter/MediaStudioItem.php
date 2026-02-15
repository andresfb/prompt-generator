<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use App\Libraries\MediaNamesLibrary;
use App\Models\Prompter\Base\BaseImagedModel;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string|null $image
 * @property string|null $trailer
 * @property bool $active
 * @property int $usages
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
final class MediaStudioItem extends BaseImagedModel
{
    use HasTags;
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function getCount(string $studioId): int
    {
        return self::query()
            ->where('media_studio_id', $studioId)
            ->count();
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(MediaStudio::class);
    }

    public function getMediaName(): string
    {
        return MediaNamesLibrary::image();
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'active' => 'boolean',
        ];
    }
}
