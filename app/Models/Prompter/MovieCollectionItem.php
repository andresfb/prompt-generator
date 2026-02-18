<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $movie_collection_id
 * @property int $movie_info_id
 * @property ?string $overview
 * @property ?string $year
 * @property ?array $tag_lines
 * @property ?array $trailers
 * @property ?array $genres
 * @property int $active
 * @property int $usages
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
final class MovieCollectionItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(MovieCollection::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'genres' => 'json',
            'tag_lines' => 'json',
            'trailers' => 'json',
        ];
    }
}
