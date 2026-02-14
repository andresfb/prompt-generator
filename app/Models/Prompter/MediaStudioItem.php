<?php

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string|null $image
 * @property string|null $trailer
 * @property boolean $active
 * @property integer $usages
 * @property-read CarbonInterface|null $published_at
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
class MediaStudioItem extends Model
{
    use SoftDeletes;

    public function studio(): BelongsTo
    {
        return $this->belongsTo(MediaStudio::class);
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'active' => 'boolean',
            'published_at' => 'timestamp',
        ];
    }
}
