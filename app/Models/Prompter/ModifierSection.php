<?php

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property bool $active
 * @property int $order
 */
class ModifierSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function items(): HasMany
    {
        return $this->hasMany(ModifierItem::class);
    }

    protected $casts = [
        'active' => 'boolean',
    ];
}
