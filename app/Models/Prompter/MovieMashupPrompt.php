<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $hash
 * @property ?string $content
 * @property bool $active
 * @property bool $generated
 * @property int $usages
 * @property ?string $provider
 * @property ?string $prompt
 * @property ?CarbonInterface $deleted_at
 * @property ?CarbonInterface $created_at
 * @property ?CarbonInterface $updated_at
 */
final class MovieMashupPrompt extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function items(): HasMany
    {
        return $this->hasMany(MovieMashupItem::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'generated' => 'boolean',
        ];
    }
}
