<?php

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $hash
 * @property string $title
 * @property string $permalink
 * @property bool $active
 * @property int $usages
 * @property CarbonInterface|null $published_at
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
class RedditWritingPrompt extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'published_at' => 'timestamp',
        ];
    }
}
