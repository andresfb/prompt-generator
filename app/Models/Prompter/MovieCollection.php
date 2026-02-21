<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

/**
 * @property int $id
 * @property string $item_id
 * @property string $name
 * @property int $count
 * @property int $active
 * @property ?array $genres
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
final class MovieCollection extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function getCount(string $itemId): int
    {
        $select = self::query()
            ->select('count')
            ->where('item_id', $itemId)
            ->first();

        if ($select === null) {
            return 0;
        }

        return $select->count;
    }

    public function items(): HasMany
    {
        return $this->hasMany(MovieCollectionItem::class);
    }

    #[Scope]
    protected function withActiveItems(Builder $query): Builder
    {
        return $query->select('movie_collections.*')
            ->where('movie_collections.active', true)
            ->join('movie_collection_items', 'movie_collections.id', '=', 'movie_collection_items.movie_collection_id')
            ->where('movie_collection_items.active', true)
            ->where('movie_collection_items.usages', '<=', Config::integer('constants.prompts_max_usages'));
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'genres' => 'json',
            'active' => 'boolean',
        ];
    }
}
