<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $text
 * @property bool $active
 * @property bool $anachronisable
 * @property int $usages
 */
final class ModifierItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(ModifierSection::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'anachronisable' => 'boolean',
        ];
    }
}
