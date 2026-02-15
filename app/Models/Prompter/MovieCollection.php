<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    protected function casts(): array
    {
        return [
            'genres' => 'json',
            'active' => 'boolean',
        ];
    }
}
