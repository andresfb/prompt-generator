<?php

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $short_name
 * @property string|null $description
 * @property string $endpoint
 * @property int $total_scenes
 * @property int $per_page
 * @property int $current_page
 * @property int $last_page
 * @property bool $active
 * @property-read CarbonInterface|null $published_at
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
class MediaStudio extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'active' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(MediaStudioItem::class);
    }
}
