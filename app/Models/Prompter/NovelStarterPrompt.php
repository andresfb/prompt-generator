<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @property-read int $id
 * @property string $hash
 * @property string $hero
 * @property string $flaw
 * @property string $genre
 * @property string|null $content
 * @property string|null $provider
 * @property string|null $prompt
 * @property bool $generated
 * @property bool $active
 * @property int $usages
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
final class NovelStarterPrompt extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'generated' => 'boolean',
        ];
    }
}
