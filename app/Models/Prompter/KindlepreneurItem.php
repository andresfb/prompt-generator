<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @property int $id
 * @property int $kindlepreneur_section_id
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
final class KindlepreneurItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(KindlepreneurSection::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
