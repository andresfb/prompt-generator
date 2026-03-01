<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @property int $id
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
final class NovelStarterItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(NovelStarterSection::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
