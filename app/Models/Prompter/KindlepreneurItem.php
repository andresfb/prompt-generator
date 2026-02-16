<?php

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $kindlepreneur_section_id
 * @property string $text
 * @property boolean $active
 * @property int $usages
 */
class KindlepreneurItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(KindlepreneurSection::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
