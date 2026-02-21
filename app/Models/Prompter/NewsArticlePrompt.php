<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $source_id
 * @property string $source
 * @property string $title
 * @property string $content
 * @property string $permalink
 * @property string $thumbnail
 * @property bool $active
 * @property int $usages
 * @property-read CarbonInterface|null $published_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
final class NewsArticlePrompt extends Model
{
    protected $guarded = ['id'];

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'published_at' => 'timestamp',
        ];
    }
}
