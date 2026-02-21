<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @property int $id
 * @property int $reddit_prompt_endpoint_id
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
final class RedditWritingPrompt extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(RedditPromptEndpoint::class, 'reddit_prompt_endpoint_id');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'published_at' => 'timestamp',
        ];
    }
}
