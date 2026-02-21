<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
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
final class MediaStudioItem extends Model
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

    #[Override]
    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'active' => 'boolean',
        ];
    }
}
