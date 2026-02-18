<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

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
final class MediaStudio extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function items(): HasMany
    {
        return $this->hasMany(MediaStudioItem::class);
    }

    #[Scope]
    protected function withActiveItems(Builder $query): Builder
    {
        return $query->select('media_studios.*')
            ->where('media_studios.active', true)
            ->join('media_studio_items', 'media_studios.id', '=', 'media_studio_items.media_studio_id')
            ->where('media_studio_items.active', true)
            ->where('media_studio_items.usages', '<=', Config::integer('constants.prompts_max_usages'));
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'active' => 'boolean',
        ];
    }
}
