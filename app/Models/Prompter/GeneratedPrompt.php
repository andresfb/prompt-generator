<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @property int $id
 * @property string $genre
 * @property string $setting
 * @property string $character
 * @property string $conflict
 * @property string $tone
 * @property string $narrative
 * @property string $period
 * @property string $content
 * @property bool $active
 * @property int $usages
 * @property string $provider
 * @property string $prompt
 * @property ?CarbonInterface $deleted_at
 * @property ?CarbonInterface $created_at
 * @property ?CarbonInterface $updated_at
 */
final class GeneratedPrompt extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'usages' => 'integer',
        ];
    }
}
